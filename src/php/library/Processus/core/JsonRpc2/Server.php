<?php

namespace Processus\Lib\JsonRpc2;

/**
 * @see: http://www.jsonrpc.org/specification
 */
class Server
    implements
    \Processus\Lib\JsonRpc2\Interfaces\ServerInterface
{

    /**
     * @var \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface
     */
    protected $_gateway;

    /**
     * @var array
     */
    protected $_serviceInfoCache = array();

    /**
     * @var \Processus\Lib\JsonRpc2\Interfaces\RpcInterface
     */
    protected $_rpc;

    /**
     * @var array
     */
    protected $_servicesList = array(

        array(
            "serviceName"                   => "Ping",
            "className"                     => "{{NAMESPACE}}\\WebService\\Ping",
            "isValidateMethodParamsEnabled" => true,
            "classMethodFilter"             => array(
                "allow" => array(
                    "*",
                ),
                "deny"  => array(
                    //'*get*',
                    "*myPrivateMethod"
                ),
            ),

        ),


    );


    /**
     * @return ServiceInfoVo
     */
    public function newServiceInfo()
    {
        return new ServiceInfoVo();
    }

    /**
     * @return array
     */
    public function getServicesList()
    {
        return $this->_servicesList;
    }


    /**
     * cache services list as dictionary
     * @return array
     */
    public function getServiceInfoCache()
    {
        if (count($this->_serviceInfoCache) < 1) {

            $dict = array();

            $servicesList = $this->getServicesList();

            $namespaceName = RpcUtil::getNamespaceName($this);

            foreach ($servicesList as $serviceConfig) {

                $serviceInfo = $this->newServiceInfo();
                $serviceInfo->setData($serviceConfig);

                $serviceClassName = $serviceInfo->getClassName();
                $serviceClassName = str_replace(
                    array(
                        '{{NAMESPACE}}',
                    ),
                    array(
                        $namespaceName,
                    ),
                    $serviceClassName
                );
                $serviceInfo->setClassName($serviceClassName);

                $dictKey        = $serviceInfo->getServiceUid();
                $dict[$dictKey] = $serviceInfo;

            }

            $this->_serviceInfoCache = $dict;

        }

        return $this->_serviceInfoCache;
    }


    /**
     * @param Interfaces\RpcInterface $rpc
     * @return Server
     */
    public function setRpc(
        \Processus\Lib\JsonRpc2\Interfaces\RpcInterface $rpc
    ) {

        $rpc->setServer($this);
        $rpc->setServerClassName(get_class($this));

        $this->_rpc = $rpc;

        return $this;
    }

    /**
     * @return Server
     */
    public function unsetRpc()
    {
        $this->_rpc = null;

        return $this;
    }

    /**
     * @return Interfaces\RpcInterface
     */
    public function getRpc()
    {

        return $this->_rpc;
    }

    /**
     * @return bool
     */
    public function hasRpc()
    {

        return (
            $this->_rpc
                instanceof
                \Processus\Lib\JsonRpc2\Interfaces\RpcInterface
        );
    }


    /**
     * @return Server
     * @throws \Exception
     */
    public function run()
    {


        if (!$this->hasGateway()) {

            throw new \Exception(
                'No server.gateway! ' . __METHOD__ . get_class($this)
            );

        }
        if (!$this->hasRpc()) {

            throw new \Exception(
                'No server.rpc! ' . __METHOD__ . get_class($this)
            );
        }

        $rpc = $this->getRpc();

        try {

            // Rpc: set server & server class
            $rpc->setServer($this);
            $rpc->setServerClassName(get_class($this));

            // Check: rpc.method exist

            if(!$rpc->hasMethod()) {

                throw new \Exception(
                    'INVALID RPC.METHOD: SERVICE NOT FOUND (SR0001)'
                );
            }

            $rpcMethod = $rpc->getMethod();
            $rpcParams = $rpc->getParams();

            // (0) Router: Find ServiceInfoVo bz rpc.method
            $serviceInfo = $this->_getServiceInfoByRpcMethod($rpcMethod);
            $rpc->setServiceInfo($serviceInfo);

            // (1) Router: ServiceClass
            $serviceClassName = $serviceInfo->getClassName();
            $rpc->setServiceClassName($serviceClassName);
            $serviceReflectionClass = RpcUtil::newReflectionClass(
                $serviceClassName, false
            );
            if(!($serviceReflectionClass instanceof \ReflectionClass)) {
                throw new \Exception(
                    'INVALID RPC.METHOD: SERVICE NOT FOUND (SR0002)'
                );
            }
            $rpc->setServiceReflectionClass($serviceReflectionClass);
            $this->_validateService();

            // (2) Router: Service-Method
            $serviceMethodName = $this->_getServiceMethodNameByRpcMethod(
                $rpcMethod
            );
            $rpc->setServiceMethodName($serviceMethodName);
            if(!$serviceReflectionClass->hasMethod($serviceMethodName)) {
                throw new \Exception(
                    'INVALID RPC.METHOD: SERVICE-METHOD NOT FOUND (X0001)'
                );
            }
            $rpc->setServiceReflectionMethod(
                $serviceReflectionClass->getMethod($serviceMethodName)
            );
            $this->_validateServiceMethod();

            // (3) Router: Service-Method-Args
            $this->_validateRpcParams($rpcParams);
            $this->_mapServiceMethodArgs($rpcParams);

            // (4) AuthCheck
            $this->_requireAuth();

            // (5) invoke
            $this->_invokeServiceMethod();

            $this->_onRpcResult();

        } catch (\Exception $e) {

            $rpc->setException($e);

            $this->_onRpcException();

        }

        return $this;
    }


    /**
     * @return Server
     * @throws \Exception
     */
    protected function _requireAuth()
    {
        $result = $this;

        $rpc = $this->getRpc();

        if (!$rpc->hasAuthModule()) {

            return $result;
        }



        $authModule = $rpc->getAuthModule();

        if ($authModule->isAuthorized()) {

            return $result;
        }

        throw new \Exception(
            GatewayErrorType::ERROR_GATEWAY_AUTH_REQUIRED
        );

    }


    /**
     * @param array $rpcParams
     * @return Server
     */
    protected function _mapServiceMethodArgs($rpcParams)
    {
        $rpc = $this->getRpc();

        $reflectionMethod = $rpc->getServiceReflectionMethod();

        if(!is_array($rpcParams)) {
            $rpcParams = array();
        }

        $reflectionMethodArgs = $reflectionMethod->getParameters();

        $serviceMethodArgs = $rpcParams;
        // named  or positional parameters?
        $isAssocArray = RpcUtil::isAssocArray($rpcParams);
        $isNamedRpcParams = $isAssocArray;
        if ($isNamedRpcParams) {
            $serviceMethodArgs = array();
            foreach ($reflectionMethodArgs as $reflectionParameter) {
                $key   = $reflectionParameter->getName();
                $value = null;
                if (array_key_exists($key, $rpcParams)) {
                    $value = $rpcParams[$key];
                } else {

                    if (
                        ($reflectionParameter->isOptional())
                        && (
                        $reflectionParameter->isDefaultValueAvailable()
                        )
                    ) {
                        $value =
                            $reflectionParameter->getDefaultValue();
                    }

                }
                $serviceMethodArgs[] = $value;
            }
        }

        $rpc->setServiceMethodArgs($serviceMethodArgs);

        return $this;
    }

    /**
     * @param array $rpcParams
     * @return Server
     * @throws \Exception
     */
    protected function _validateRpcParams($rpcParams)
    {
        $result = $this;

        $rpc = $this->getRpc();
        $serviceInfo = $rpc->getServiceInfo();
        $reflectionMethod = $rpc->getServiceReflectionMethod();

        if(!is_array($rpcParams)) {
            $rpcParams = array();
        }

        $methodArgs = $rpcParams;

        if (!$serviceInfo->getIsValidateMethodParamsEnabled()) {

            return $result;
        }

        $reflectionParameters = $reflectionMethod->getParameters();

        $methodArgs        = (array)$methodArgs;
        $numParamsExpected = $reflectionMethod->getNumberOfParameters();
        $numParamsRequired =
            $reflectionMethod->getNumberOfRequiredParameters();
        $numParamsOptional = $numParamsExpected - $numParamsRequired;
        $numParamsGiven    = count($methodArgs);
        $numParamsMissing  =
            $numParamsExpected - $numParamsGiven - $numParamsOptional;

        if ($numParamsMissing > 0) {

            $paramNames         = array();
            $paramNamesGiven    = array_keys($methodArgs);
            $paramNamesOptional = array();
            $paramNamesRequired = array();
            $paramNamesMissing  = array();
            foreach ($reflectionParameters as $reflectionParameter) {
                $paramName    = $reflectionParameter->getName();
                $paramNames[] = $paramName;
                if ($reflectionParameter->isOptional()) {
                    $paramNamesOptional[] = $paramName;
                } else {
                    $paramNamesRequired[] = $paramName;

                    if (!in_array($paramName, $paramNamesGiven, true)) {
                        $paramNamesMissing[] = $paramName;
                    }

                }
            }

            throw new \Exception(
                'INVALID RPC.PARAMS: ['
                    . ' Missing: ' . $numParamsMissing
                    . ' (' . implode(', ', $paramNamesMissing) . ')'
                    . ' Expected: ' . $numParamsExpected
                    . ' (' . implode(', ', $paramNames) . ')'
                    . ' Required: ' . $numParamsRequired
                    . ' (' . implode(', ', $paramNamesRequired) . ')'
                    . ' Optional: ' . $numParamsOptional
                    . ' (' . implode(', ', $paramNamesOptional) . ')'
                    . ' Given: ' . $numParamsGiven
                    . ' (' . implode(', ', $paramNamesGiven) . ')'
                    . ']'
            );

        }

        return $result;
    }


    /**
     * @return Server
     * @throws \Exception
     */
    protected function _validateService()
    {

        $rpc = $this->getRpc();
        $serviceReflectionClass = $rpc->getServiceReflectionClass();
        if(!($serviceReflectionClass instanceof \ReflectionClass)) {
            throw new \Exception(
                'INVALID RPC.METHOD: SERVICE NOT FOUND (VS0001)'
            );


        }

        if(!($serviceReflectionClass->implementsInterface(
            '\Processus\Lib\JsonRpc2\Interfaces\ServiceInterface'
        ))) {
            throw new \Exception(
                'INVALID RPC.METHOD: SERVICE NOT FOUND (VS0002)'
            );
        }

        return $this;

    }

    /**
     * @return Server
     * @throws \Exception
     */
    protected function _validateServiceMethod(
    ) {

        $rpc = $this->getRpc();

        $reflectionMethod = $rpc->getServiceReflectionMethod();
        $serviceInfo = $rpc->getServiceInfo();

        $methodName = $reflectionMethod->getName();

        $allowMethods = (array)$serviceInfo->getClassMethodFilterAllow();
        $denyMethods  = (array)$serviceInfo->getClassMethodFilterDeny();
        if (defined(FNM_CASEFOLD)) {
            define('FNM_CASEFOLD', 16);
        }

        $isMatched = false;
        foreach ($allowMethods as $pattern) {
            $isMatched = fnmatch(
                '' . $pattern,
                $methodName,
                FNM_CASEFOLD
            );
            if ($isMatched) {

                break;
            }
        }
        $isAllowed = ($isMatched === true);
        if (!$isAllowed) {
            throw new \Exception(
                'INVALID RPC.METHOD: ACCESS DENIED (ISV000F1) '
            );
        }

        $isMatched = false;
        foreach ($denyMethods as $pattern) {
            $isMatched = fnmatch(
                '' . $pattern,
                $methodName,
                FNM_CASEFOLD
            );
            if ($isMatched) {

                break;
            }
        }
        $isDenied = ($isMatched === true);
        if ($isDenied) {
            throw new \Exception(
                'INVALID RPC.METHOD: ACCESS DENIED (ISV000F2) '
            );
        }

        if (strpos($methodName, '-') !== false) {

            throw new \Exception(
                'INVALID RPC.METHOD: ACCESS DENIED (ISV000R1) '
            );

        }
        if (!$reflectionMethod->isPublic()) {

            throw new \Exception(
                'INVALID RPC.METHOD : ACCESS DENIED (ISV000R2) '
            );
        }
        if ($reflectionMethod->isStatic()) {

            throw new \Exception(
                'INVALID RPC.METHOD : ACCESS DENIED (ISV000R3) '
            );
        }

        return $this;
    }

    /**
     * @param $rpcMethod string
     * @return ServiceInfoVo
     */
    protected function _getServiceInfoByRpcMethod($rpcMethod)
    {
        $result = new ServiceInfoVo();

        if (empty($rpcMethod)) {

            return $result;
        }

        $rpcMethodParsed = RpcUtil::parseRpcMethod($rpcMethod);

        $rpcQualifiedClassName =
            $rpcMethodParsed['rpcQualifiedClassName'];

        $serviceInfo = $this->newServiceInfo();
        $serviceInfo->setServiceName($rpcQualifiedClassName);
        $servicesDictionaryKey = '' . $serviceInfo->getServiceUid();
        unset($serviceInfo);

        $servicesDictionary = $this->getServiceInfoCache();

        $serviceInfo = null;
        if (array_key_exists(
            $servicesDictionaryKey,
            $servicesDictionary
        )
        ) {
            $serviceInfo = $servicesDictionary[$servicesDictionaryKey];
        }
        if (!($serviceInfo instanceof ServiceInfoVo)) {

            return $result;
        }

        return $serviceInfo;

    }

    /**
     * @param $rpcMethod string
     * @return string
     */
    protected function _getServiceMethodNameByRpcMethod($rpcMethod)
    {
        $rpcMethodParsed        = RpcUtil::parseRpcMethod($rpcMethod);
        $serviceClassMethodName = $rpcMethodParsed['rpcMethodName'];

        return '' . $serviceClassMethodName;
    }


    /**
     * @return Server
     * @throws \Exception
     */
    protected function _invokeServiceMethod()
    {

        $rpc = $this->getRpc();

        $serviceReflectionClass = $rpc->getServiceReflectionClass();
        $serviceReflectionMethod = $rpc->getServiceReflectionMethod();
        $serviceMethodArgs = $rpc->getServiceMethodArgs();

        $serviceInstance = null;
        try {

            $serviceInstance = $serviceReflectionClass
                ->newInstanceArgs(array())
            ;

        } catch(\Exception $e) {

            throw new \Exception(
                'Error while create service instance. (SISM0001)
                ');

        }

        $serviceResult = $serviceReflectionMethod->invokeArgs(
            $serviceInstance,
            $serviceMethodArgs
        );

        $rpc->setResult($serviceResult);

        return $this;
    }


    /**
     * @return Server
     */
    protected function _onRpcResult()
    {
        // do fancy stuff here

        $rpc = $this->getRpc();

        return $this;
    }

    /**
     * @return Server
     */
    protected function _onRpcException()
    {
        // do fancy stuff here

        $rpc = $this->getRpc();

        return $this;
    }


    /**
     * @param Interfaces\GatewayInterface $gateway
     * @return Interfaces\ServerInterface|Server
     */
    public function setGateway(
        \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface $gateway
    ) {
        $this->_gateway = $gateway;

        return $this;
    }

    /**
     * @return Interfaces\ServerInterface|Server
     */
    public function unsetGateway()
    {
        $this->_gateway = null;

        return $this;
    }

    /**
     * @return Interfaces\GatewayInterface|null
     */
    public function getGateway()
    {
        return $this->_gateway;
    }

    /**
     * @return bool
     */
    public function hasGateway()
    {

        return ($this->_gateway instanceof Interfaces\GatewayInterface);
    }
}
