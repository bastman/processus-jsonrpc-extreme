<?php

namespace Processus\Lib\JsonRpc2;

    class Rpc implements
        \Processus\Lib\JsonRpc2\Interfaces\RpcInterface
    {

        /**
         * @var string
         */
        protected $_method = '';
        /**
         * @var array
         */
        protected $_params = array();
        /**
         * @var null|array|mixed
         */
        protected $_authData = null;


        /**
         * @var string
         */
        protected $_serverClassName = '';
        /**
         * @var string
         */
        protected $_gatewayClassName = '';

        /**
         * @var string
         */
        protected $_authModuleClassName = '';

        /**
         * @var string
         */
        protected $_cryptModuleClassName = '';


        /**
         * @var string
         */
        protected $_serviceClassName;
        /**
         * @var string
         */
        protected $_serviceMethodName;

        /**
         * @var array
         */
        protected $_serviceMethodArgs;

        /**
         * @var \Processus\Lib\JsonRpc2\Interfaces\ServiceInfoVoInterface|null
         */
        protected $_serviceInfo;


        /**
         * @var $request \Processus\Lib\JsonRpc2\Interfaces\RpcRequestVoInterface
         */
        protected $_request;
        /**
         * @var $response \Processus\Lib\JsonRpc2\Interfaces\RpcResponseVoInterface
         */
        protected $_response;


        /**
         * @var mixed
         */
        protected $_result;

        /**
         * @var \Exception|null
         */
        protected $_exception;



        /**
         * @var \ReflectionMethod|null
         */
        protected $_serviceReflectionMethod;

        /**
         * @var \ReflectionClass|null
         */
        protected $_serviceReflectionClass;


        /**
         * @var \Processus\Lib\JsonRpc2\Interfaces\ServiceInterface|null
         */
        protected $_service;

        /**
         * @var \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface
         */
        protected $_gateway;

        /**
         * @var Interfaces\ServerInterface
         */
        protected $_server;

        /**
         * @var Interfaces\AuthModuleInterface|null
         */
        protected $_authModule;

        /**
         * @var Interfaces\CryptModuleInterface|null
         */
        protected $_cryptModule;


        /**
         * @return \Processus\Lib\JsonRpc2\Interfaces\RpcRequestVoInterface
         */
        public function newRequest()
        {
            $request = new RpcRequestVo();

            return $request;
        }


        /**
         * @return \Processus\Lib\JsonRpc2\Interfaces\RpcRequestVoInterface
         */
        public function getRequest()
        {
            if(!$this->hasRequest()) {
                $this->_request = $this->newRequest();
            }

            return $this->_request;
        }

        /**
         * @return bool
         */
        public function hasRequest()
        {
            return (
                $this->_request
                    instanceof
                    \Processus\Lib\JsonRpc2\Interfaces\RpcRequestVoInterface
            );
        }

        /**
         * @param \Processus\Lib\JsonRpc2\Interfaces\RpcRequestVoInterface $request
         * @return Rpc
         */
        public function setRequest(
            \Processus\Lib\JsonRpc2\Interfaces\RpcRequestVoInterface $request
        )
        {
            $this->_request = $request;

            return $this;
        }

        /**
         * @return Rpc
         */
        public function unsetRequest()
        {
            $this->_request = null;

            return $this;
        }


        /**
         * @return \Processus\Lib\JsonRpc2\Interfaces\RpcResponseVoInterface
         */
        public function newResponse()
        {
            $response = new RpcResponseVo();

            return $response;
        }
        /**
         * @return \Processus\Lib\JsonRpc2\Interfaces\RpcResponseVoInterface
         */
        public function getResponse()
        {
            if(!$this->hasResponse()) {
                $this->_response = $this->newResponse();
            }

            return $this->_response;
        }

        /**
         * @return bool
         */
        public function hasResponse()
        {
            return (
                $this->_response
                    instanceof
                    \Processus\Lib\JsonRpc2\Interfaces\RpcResponseVoInterface
            );
        }


        /**
         * @param $className string
         * @return Rpc
         */
        public function setServiceClassName($className)
        {
            if(!is_string($className)) {
                $className = '';
            }
            $this->_serviceClassName = $className;

            return $this;
        }

        /**
         * @return string
         */
        public function getServiceClassName()
        {
            $result = '';

            $value = $this->_serviceClassName;

            if(!is_string($value)) {

                return $result;
            }

            return $value;
        }

        /**
         * @return Rpc
         */
        public function unsetServiceClassName()
        {
            $this->_serviceClassName = '';

            return $this;
        }

        /**
         * @return bool
         */
        public function hasServiceClassName()
        {
            $value = $this->getServiceClassName();

            return (
                (is_string($value))
                    && (!empty($value))
            );
        }


        /**
         * @param $methodName string
         * @return Rpc
         */
        public function setServiceMethodName($methodName)
        {
            if(!is_string($methodName)) {
                $methodName = '';
            }
            $this->_serviceMethodName = $methodName;

            return $this;
        }

        /**
         * @return string
         */
        public function getServiceMethodName()
        {
            $result = '';

            $value = $this->_serviceMethodName;

            if(!is_string($value)) {

                return $result;
            }

            return $value;
        }

        /**
         * @return Rpc
         */
        public function unsetServiceMethodName()
        {
            $this->_serviceMethodName = '';

            return $this;
        }

        /**
         * @return bool
         */
        public function hasServiceMethodName()
        {
            $value = $this->getServiceMethodName();

            return (
                (is_string($value))
                    && (!empty($value))
            );
        }


        /**
         * @param $methodArgs array
         * @return Rpc
         */
        public function setServiceMethodArgs($methodArgs = array())
        {
            if(!is_array($methodArgs)) {
                $methodArgs = array();
            }
            $this->_serviceMethodArgs = $methodArgs;

            return $this;
        }

        /**
         * @return string
         */
        public function getServiceMethodArgs()
        {
            if(!is_array($this->_serviceMethodArgs)) {
                $this->_serviceMethodArgs = array();
            }

            return $this->_serviceMethodArgs;
        }

        /**
         * @return Rpc
         */
        public function unsetServiceMethodArgs()
        {
            $this->_serviceMethodArgs = array();

            return $this;
        }



        /**
         * @param \Processus\Lib\JsonRpc2\Interfaces\RpcResponseVoInterface $response
         * @return Rpc
         */
        public function setResponse(
            \Processus\Lib\JsonRpc2\Interfaces\RpcResponseVoInterface $response
        )
        {
            $this->_response = $response;

            return $this;
        }

        /**
         * @param \ReflectionMethod $reflectionMethod
         * @return Rpc
         */
        public function setServiceReflectionMethod(
            \ReflectionMethod $reflectionMethod
        )
        {

            $this->_serviceReflectionMethod = $reflectionMethod;

            return $this;
        }

        /**
         * @return Rpc
         */
        public function unsetServiceReflectionMethod()
        {
            $this->_serviceReflectionMethod = null;

            return $this;
        }
        /**
         * @return \ReflectionMethod|null
         */
        public function getServiceReflectionMethod()
        {
            return $this->_serviceReflectionMethod;
        }

        /**
         * @return bool
         */
        public function hasServiceReflectionMethod()
        {

            return (
                $this->_serviceReflectionMethod instanceof \ReflectionMethod
            );
        }

        /**
         * @param \ReflectionClass $reflectionClass
         * @return Rpc
         */
        public function setServiceReflectionClass(
            \ReflectionClass $reflectionClass
        )
        {

            $this->_serviceReflectionClass = $reflectionClass;

            return $this;
        }

        /**
         * @return Rpc
         */
        public function unsetServiceReflectionClass()
        {
            $this->_serviceReflectionClass = null;

            return $this;
        }
        /**
         * @return \ReflectionClass|null
         */
        public function getServiceReflectionClass()
        {

            return $this->_serviceReflectionClass;
        }

        /**
         * @return bool
         */
        public function hasServiceReflectionClass()
        {

            return (
                $this->_serviceReflectionClass instanceof \ReflectionClass
            );
        }

        /**
         * @return object|null
         */
        public function getService()
        {
            return $this->_service;
        }

        /**
         * @param object|null $serviceInstance
         * @return Rpc
         */
        public function setService(
            \Processus\Lib\JsonRpc2\Interfaces\ServiceInterface $serviceInstance
        )
        {
            $this->_service = $serviceInstance;

            return $this;
        }

        /**
         * @return Rpc
         */
        public function unsetService()
        {
                $this->_service = null;

            return $this;
        }

        public function hasService()
        {

            return (
                $this->_service
                    instanceof
                    \Processus\Lib\JsonRpc2\Interfaces\ServiceInterface
            );

        }

        /**
         * @param string $gatewayClassName
         * @return Interfaces\RpcInterface|Rpc
         */
        public function setGatewayClassName($gatewayClassName)
        {
            $result = $this;

            $this->_gatewayClassName = $gatewayClassName;

            return $result;
        }

        /**
         * @return string
         */
        public function getGatewayClassName()
        {
            return (string)$this->_gatewayClassName;
        }

        /**
         * @param Interfaces\GatewayInterface $gateway
         * @return Interfaces\RpcInterface|Rpc
         */
        public function setGateway(
            \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface $gateway
        )
        {
            $result = $this;

            $this->_gateway = $gateway;

            return $result;
        }

        /**
         * @return Interfaces\RpcInterface|Rpc
         */
        public function unsetGateway()
        {
            $result = $this;

            $this->_gateway = null;

            return $result;
        }

        /**
         * @return bool
         */
        public function hasGateway()
        {
            return (
                $this->_gateway
                    instanceof
                    \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface
            );
        }











        /**
         * @param string $serverClassName
         * @return Interfaces\RpcInterface|Rpc
         */
        public function setServerClassName($serverClassName)
        {
            $result = $this;

            $this->_serverClassName = $serverClassName;

            return $result;
        }

        /**
         * @return string
         */
        public function getServerClassName()
        {
            return (string)$this->_serverClassName;
        }

        /**
         * @param Interfaces\ServerInterface $server
         * @return Interfaces\RpcInterface|Rpc
         */

        public function setServer(Interfaces\ServerInterface $server)
        {
            $result = $this;

            $this->_server = $server;

            return $result;
        }




        /**
         * @return Interfaces\RpcInterface|Rpc
         */
        public function unsetServer()
        {
            $result = $this;

            $this->_server = null;

            return $result;
        }

        /**
         * @return bool
         */
        public function hasServer()
        {
            return (
                $this->_server
                    instanceof
                    \Processus\Lib\JsonRpc2\Interfaces\ServerInterface
            );
        }








        /**
         * @param string $authModuleClassName
         * @return Interfaces\RpcInterface|Rpc
         */
        public function setAuthModuleClassName($authModuleClassName)
        {
            $result = $this;

            $this->_authModuleClassName = $authModuleClassName;

            return $result;
        }

        /**
         * @return string
         */
        public function getAuthModuleClassName()
        {
            return (string)$this->_authModuleClassName;
        }

        /**
         * @param Interfaces\AuthModuleInterface $authModule
         * @return Interfaces\RpcInterface|Rpc
         */
        public function setAuthModule(
            Interfaces\AuthModuleInterface $authModule
        )
        {
            $result = $this;

            $this->_authModule = $authModule;
            $authModule->setRpc($this);
            return $result;
        }

        /**
         * @return Interfaces\RpcInterface|Rpc
         */
        public function unsetAuthModule()
        {
            $result = $this;

            $this->_authModule = null;

            return $result;
        }

        /**
         * @return null|Interfaces\AuthModuleInterface
         */
        public function getAuthModule()
        {

            return $this->_authModule;
        }
        /**
         * @return bool
         */
        public function hasAuthModule()
        {
            return (
                $this->_authModule
                    instanceof
                    \Processus\Lib\JsonRpc2\Interfaces\AuthModuleInterface
            );
        }









        /**
         * @param Interfaces\ServiceInfoVoInterface $serviceInfo
         * @return Interfaces\RpcInterface|Rpc
         */
        public function setServiceInfo(
            \Processus\Lib\JsonRpc2\Interfaces\ServiceInfoVoInterface
            $serviceInfo
        )
        {
            $this->_serviceInfo = $serviceInfo;

            return $this;
        }

        /**
         * @return Interfaces\RpcInterface|Rpc
         */
        public function unsetServiceInfo()
        {
            $this->_serviceInfo = null;

            return $this;
        }

        /**
         * @return Interfaces\ServiceInfoVoInterface|null
         */
        public function getServiceInfo()
        {

            return $this->_serviceInfo;
        }

        /**
         * @return bool
         */
        public function hasServiceInfo()
        {

            return (
                $this->_serviceInfo
                    instanceof
                    \Processus\Lib\JsonRpc2\Interfaces\ServiceInfoVoInterface
            );
        }


        /**
         * @param $result
         * @return Rpc
         */
        public function setResult($result)
        {
            $this->_result = $result;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getResult()
        {
            return $this->_result;
        }

        /**
         * @param \Exception $exception
         * @return Rpc
         */
        public function setException(\Exception $exception)
        {
            $this->_exception = $exception;

            return $this;
        }

        /**
         * @return Rpc
         */
        public function unsetException()
        {
            $this->_exception = null;

            return $this;
        }

        /**
         * @return \Exception|null
         */
        public function getException()
        {

            return $this->_exception;
        }

        /**
         * @return bool
         */
        public function hasException()
        {

            return ($this->getException() instanceof \Exception);
        }


        /**
         * @param string $method
         * @return Rpc
         */
        public function setMethod($method)
        {
            $this->_method = $method;

            return $this;
        }

        /**
         * @return string
         */
        public function getMethod()
        {
            return '' . $this->_method;
        }

        /**
         * @return bool
         */
        public function hasMethod()
        {
            $result = false;

            $value = $this->getMethod();
            if(!is_string($value)) {

                return $result;
            }

            return (!empty($value));

        }

        /**
         * @param array $params
         * @return Rpc
         */
        public function setParams($params = array())
        {
            $this->_params = $params;

            return $this;
        }

        /**
         * @return array
         */
        public function getParams()
        {
            $result= array();

            $value = $this->_params;

            if(!is_array($value)) {

                return $result;
            }

            return $value;
        }

        /**
         * @param array|mixed|null $authData
         * @return Rpc
         */
        public function setAuthData($authData)
        {
            $this->_authData = $authData;

            return $this;
        }

        /**
         * @return array|mixed|null
         */
        public function getAuthData()
        {

            return $this->_authData;
        }


        /**
         * @param string $cryptModuleClassName
         * @return Interfaces\RpcInterface
         */
        public function setCryptModuleClassName($cryptModuleClassName)
        {
            $this->_cryptModuleClassName = $cryptModuleClassName;

            return $this;
        }

        /**
         * @return string
         */
        public function getCryptModuleClassName()
        {
            return (string)$this->_cryptModuleClassName;
        }

        /**
         * @param Interfaces\CryptModuleInterface $cryptModule
         * @return Interfaces\RpcInterface|Rpc
         */
        public function setCryptModule(
            Interfaces\CryptModuleInterface $cryptModule
        )
        {
            $this->_cryptModule = $cryptModule;
            $cryptModule->setRpc($this);

            return $this;
        }

        /**
         * @return Interfaces\CryptModuleInterface|null
         */
        public function getCryptModule()
        {
            return $this->_cryptModule;
        }

        /**
         * @return Interfaces\RpcInterface
         */
        public function unsetCryptModule()
        {
            $this->_cryptModule = null;

            return $this;
        }

        /**
         * @return bool
         */
        public function hasCryptModule()
        {
            return (
                $this->_cryptModule
                    instanceof
                    Interfaces\CryptModuleInterface
            );
        }
    }


