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
                "serviceName" => "Ping",
                "className" => "{{NAMESPACE}}\\WebService\\Ping",
                "isValidateMethodParamsEnabled" => true,
                "classMethodFilter" => array(
                    "allow" => array(
                        "*",
                    ),
                    "deny" => array(
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

                    $dictKey = $serviceInfo->getServiceUid();
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
        )
        {

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
        public function run(
        )
        {



            if(!$this->hasGateway()) {

                throw new \Exception(
                    'No server.gateway! '.__METHOD__.get_class($this)
                );

            }
            if(!$this->hasRpc()) {

                throw new \Exception(
                    'No server.rpc! '.__METHOD__.get_class($this)
                );
            }

            $rpc = $this->getRpc();

            try {

                $rpc->setServer($this);
                $rpc->setServerClassName(get_class($this));


                $request = $rpc->getRequest();

                if(!$request->hasMethod()) {

                    throw new \Exception('Invalid rpc.method is empty');
                }

                $response = $rpc->getResponse();

                $rpcMethod = $request->getMethod();
                $rpcParams = $request->getParams();

                $serviceInfo = $this->_getServiceInfoByRpcMethod($rpcMethod);
                $rpc->setServiceInfo($serviceInfo);

                $serviceClassName = $serviceInfo->getClassName();
                $rpc->setServiceClassName($serviceClassName);

                $serviceClassInstance = null;
                try {

                    if ((!empty($serviceClassName)) && (class_exists(
                        $serviceClassName
                    ))
                    ) {
                        $serviceClassInstance = new $serviceClassName();
                        $rpc->setService($serviceClassInstance);
                    }

                } catch (\Exception $e) {
                    // NOP
                }
                if (!($serviceClassInstance instanceof Service)) {
                    throw new \Exception(
                        'INVALID RPC.METHOD: SERVICE NOT FOUND'
                    );
                }

                $serviceMethodName = $this->_getServiceMethodNameByRpcMethod(
                    $rpcMethod
                );
                $rpc->setServiceMethodName($serviceMethodName);

                $reflectionClass = new \ReflectionClass($serviceClassInstance);
                $rpc->setServiceReflectionClass($reflectionClass);
                if (!$reflectionClass->hasMethod($serviceMethodName)) {

                    throw new \Exception(
                        'INVALID RPC.METHOD: SERVICE-METHOD NOT FOUND'
                    );
                }

                $reflectionMethod = $reflectionClass->getMethod(
                    $serviceMethodName
                );
                $rpc->setServiceReflectionMethod($reflectionMethod);

                $this->_validateServiceMethod(
                    $reflectionMethod,
                    $serviceInfo
                );

                $reflectionMethodArgs = $reflectionMethod->getParameters();
                $this->_validateServiceMethodArgs(
                    $reflectionMethod,
                    $serviceInfo,
                    $rpcParams
                );
                $serviceMethodArgs = $rpcParams;
                // named  or positional parameters?
                $isNamedRpcParams = (array_keys($rpcParams)
                    !== range(0, count($rpcParams) - 1)
                );
                if ($isNamedRpcParams) {
                    $serviceMethodArgs = array();
                    foreach ($reflectionMethodArgs as $reflectionParameter) {
                        $key = $reflectionParameter->getName();
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

                $this->_requireAuth();

                $serviceResult = $this->_invokeServiceMethod(
                    $rpc,
                    $serviceClassInstance,
                    $reflectionMethod,
                    $serviceMethodArgs
                );
                $rpc->setResult($serviceResult);

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

            $authData = null;
            //implement(!) in final class. fetch authdata from rpc

            $rpc = $this->getRpc();

            if(!$rpc->hasAuthModule()) {

                return $result;
            }

            $authModule = $rpc->getAuthModule();
            $authModule->setAuthData($authData);

            if($authModule->isAuthorized()) {

                return $result;
            }

            throw new \Exception(
                GatewayErrorType::ERROR_GATEWAY_AUTH_REQUIRED
            );

        }




        /**
         * @param \ReflectionMethod $reflectionMethod
         * @param ServiceInfo $serviceInfo
         * @param array $methodArgs
         * @throws \Exception
         */
        protected function _validateServiceMethodArgs(
            \ReflectionMethod $reflectionMethod,
            ServiceInfo $serviceInfo,
            array $methodArgs = array()
        ) {

            if (!$serviceInfo->getIsValidateMethodParamsEnabled()) {

                return;
            }

            $reflectionParameters = $reflectionMethod->getParameters();

            $methodArgs = (array)$methodArgs;
            $numParamsExpected = $reflectionMethod->getNumberOfParameters();
            $numParamsRequired =
                $reflectionMethod->getNumberOfRequiredParameters();
            $numParamsOptional = $numParamsExpected - $numParamsRequired;
            $numParamsGiven = count($methodArgs);
            $numParamsMissing =
                $numParamsExpected - $numParamsGiven - $numParamsOptional;

            if ($numParamsMissing > 0) {

                $paramNames = array();
                $paramNamesGiven = array_keys($methodArgs);
                $paramNamesOptional = array();
                $paramNamesRequired = array();
                $paramNamesMissing = array();
                foreach ($reflectionParameters as $reflectionParameter) {
                    $paramName = $reflectionParameter->getName();
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


        }


        /**
         * @param \ReflectionMethod $reflectionMethod
         * @param ServiceInfo $serviceInfo
         * @throws \Exception
         */
        protected function _validateServiceMethod(
            \ReflectionMethod $reflectionMethod,
            ServiceInfo $serviceInfo
        ) {
            $methodName = $reflectionMethod->getName();

            $allowMethods = (array)$serviceInfo->getClassMethodFilterAllow();
            $denyMethods = (array)$serviceInfo->getClassMethodFilterDeny();
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

        }

        /**
         * @param $rpcMethod string
         * @return ServiceInfoVo
         */
        protected function _getServiceInfoByRpcMethod($rpcMethod)
        {
            $result = new ServiceInfoVo();

            if(empty($rpcMethod)) {

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
            $rpcMethodParsed = RpcUtil::parseRpcMethod($rpcMethod);
            $serviceClassMethodName = $rpcMethodParsed['rpcMethodName'];

            return '' . $serviceClassMethodName;
        }


        /**
         * @param RPC $rpc
         * @param Service $service
         * @param \ReflectionMethod $reflectionMethod
         * @param array $params
         * @return mixed
         */
        protected function _invokeServiceMethod(
            RPC $rpc,
            Service $service,
            \ReflectionMethod $reflectionMethod,
            array $params = array()
        ) {

            $serviceResult = $reflectionMethod->invokeArgs(
                $service,
                $params
            );

            return $serviceResult;

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
        )
        {
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
