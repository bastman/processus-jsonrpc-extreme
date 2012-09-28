<?php

namespace Processus\Lib\JsonRpc2;

    class Gateway
        implements
        \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface
    {

        /**
         * @var Interfaces\CryptModuleInterface
         */
        protected $_cryptModule;

        /**
         * @var bool
         */
        protected $_isDebugEnabled = false;

        /**
         * @var $_server Server
         */
        protected $_server;

        /**
         * @var $_server Connection
         */
        protected $_connection;

        /**
         * @var \Processus\Lib\JsonRpc2\Interfaces\AuthModuleInterface
         */
        protected $_authModule;


        /**
         * @var \Processus\Lib\JsonRpc2\Interfaces\RpcQueueInterface|\Processus\Lib\JsonRpc2\RpcQueue
         */
        protected $_rpcQueue;


        /**
         * @var string
         */
        protected $_requestText = '';
        /**
         * @var array
         */
        protected $_requestData = null;

        protected $_responseData = null;

        /**
         * @var array
         */
        protected $_responseHeaders = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json; charset=utf-8",
        );

        /**
         * @var array
         */
        protected $_config = array(
            'enabled' => true,
            'requestBatchMaxItems' => 100,
            'serverClassName' => '{{NAMESPACE}}\\Server',
            'authModuleClassName' => '{{NAMESPACE}}\\AuthModule',
            'cryptModuleClassName' => '{{NAMESPACE}}\\CryptModule',
        );


        /**
         * @param $key string
         * @return mixed|null
         */
        public function getConfigValue($key)
        {
            $result = null;

            $config = $this->_config;

            if (!array_key_exists($key, $config)) {

                return $result;
            }

            return $config[$key];
        }

        /**
         * @param $value bool
         * @return Gateway
         */
        public function setIsDebugEnabled($value)
        {
            $this->_isDebugEnabled = ($value === true);

            return $this;
        }

        /**
         * @return bool
         */
        public function getIsDebugEnabled()
        {
            return ($this->_isDebugEnabled === true);
        }





        // =========== SERVER ======================

        /**
         * @return \Processus\Lib\JsonRpc2\Interfaces\ServerInterface $server
         */
        public function newServer()
        {
            $serverClassName = '' . $this->getConfigValue('serverClassName');

            $namespaceName =
                \Processus\Lib\JsonRpc2\RpcUtil::getNamespaceName(
                $this
            );

            $serverClassName = str_replace(
                array(
                    '{{NAMESPACE}}',
                ),
                array(
                    $namespaceName,

                ),
                $serverClassName
            );

            try {

                /**
                 * @var $server \Processus\Lib\JsonRpc2\Interfaces\ServerInterface $server
                 */
                $server = new $serverClassName();

            } catch(\Exception $e) {
                // NOP
            }

            if(!
            ($server instanceof
                \Processus\Lib\JsonRpc2\Interfaces\ServerInterface)
            ) {
                throw new \Exception(
                    GatewayErrorType::ERROR_GATEWAYCONFIG_INVALID_SERVER
                );
            }

            $server->setGateway($this);

            return $server;

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
         * @return \Processus\Lib\JsonRpc2\Interfaces\ServerInterface $server
         */
        public function getServer()
        {
            if(!$this->hasServer()) {
                $this->_server = $this->newServer();
            }

            return $this->_server;
        }

        /**
         * @param \Processus\Lib\JsonRpc2\Interfaces\ServerInterface $server
         * @return Gateway
         */
        public function setServer(
            \Processus\Lib\JsonRpc2\Interfaces\ServerInterface $server
        )
        {
            $server->setGateway($this);
            $this->_server = $server;

            return $this;
        }

        /**
         * @return Gateway
         */
        public function unsetServer()
        {
            $this->_server = null;

            return $this;
        }





        /**
         * @return bool
         */
        public function hasConnection()
        {
            return (
                $this->_connection
                    instanceof
                    \Processus\Lib\JsonRpc2\Interfaces\ConnectionInterface
            );
        }

        /**
         * @return \Processus\Lib\JsonRpc2\Interfaces\ConnectionInterface
         */
        public function getConnection()
        {

            return $this->_connection;
        }

        /**
         * @param \Processus\Lib\JsonRpc2\Interfaces\ConnectionInterface $connection
         * @return Gateway
         */
        public function setConnection(
            \Processus\Lib\JsonRpc2\Interfaces\ConnectionInterface $connection
        )
        {
            $connection->setGateway($this);
            $this->_connection = $connection;

            return $this;
        }

        /**
         * @return Gateway
         */
        public function unsetConnection()
        {
            $this->_connection = null;

            return $this;
        }



        // =========== RPC ======================


        /**
         * @return Rpc
         */
        public function newRpc()
        {
            return new Rpc();
        }





        // =========== REQUEST: DATA ======================


        /**
         * @param array $data|null
         * @return Gateway
         */
        public function setRequestData($data)
        {
            $this->_requestData = $data;

            return $this;
        }

        /**
         * @return array
         */
        public function getRequestData()
        {
            return $this->_requestData;
        }

        /**
         * @return bool
         */
        public function hasRequestData()
        {
            return is_array($this->_requestData);
        }

        /**
         * @return Gateway
         */
        public function unsetRequestData()
        {
            $this->_requestData = null;

            return $this;
        }

        /**
         * @return Gateway
         * @throws \Exception
         */
        protected function _decodeRequest()
        {
            $result = $this;

            $requestData = null;
            if($this->hasRequestData()) {

                return $result;
            }

            if(!$this->hasConnection()) {
                throw new \Exception(
                    'Invalid connection. '.__METHOD__.get_class($this)
                );
            }

            $connection = $this->getConnection();
            $requestText = '' . $connection->read();

            $requestData = \Processus\Lib\JsonRpc2\RpcUtil::jsonDecode(
                $requestText, true, false
            );

            if(!is_array($requestData)) {
                $requestData = null;
            }

            $this->_requestData = $requestData;

            return $result;
        }


        /**
         * @return Gateway
         * @throws \Exception
         */
        protected function _validateRequestData()
        {
            $result = $this;

            if($this->hasRequestData()) {

                return $result;
            }

            $requestData = $this->getRequestData();

            $isValid = (
                (is_array($requestData))
                    && (count(array_keys($requestData)) >0)
            );

            if(!$isValid) {
                throw new \Exception(
                    GatewayErrorType::ERROR_GATEWAY_INVALID_REQUEST
                );
            }

            return $result;
        }


        // =========== RPC: QUEUE ======================

        /**
         * @return \Processus\Lib\JsonRpc2\RpcQueue
         */
        public function newRpcQueue()
        {
            $rpcQueue = new \Processus\Lib\JsonRpc2\RpcQueue();

            return $rpcQueue;
        }

        /**
         * @return \Processus\Lib\JsonRpc2\Interfaces\RpcQueueInterface|\Processus\Lib\JsonRpc2\RpcQueue
         */
        public function getRpcQueue()
        {
            if(!($this->_rpcQueue
                instanceof
                \Processus\Lib\JsonRpc2\Interfaces\RpcQueueInterface)) {
                $this->_rpcQueue = $this->newRpcQueue();
            }

            return $this->_rpcQueue;
        }


        // =========== RPC: QUEUE-ITEM ======================

        /**
         * @param Interfaces\RpcInterface $rpc
         * @return Gateway
         */
        protected function _onBeforeInvokeRpcQueueItem(
            \Processus\Lib\JsonRpc2\Interfaces\RpcInterface $rpc
        )
        {
            // your fancy hooks here


            return $this;
        }

        /**
         * @param Interfaces\RpcInterface $rpc
         * @return Gateway
         */
        protected function _onAfterInvokeRpcQueueItem(
            \Processus\Lib\JsonRpc2\Interfaces\RpcInterface $rpc
        )
        {
            // your fancy hooks here


            return $this;
        }
        /**
         * @param Rpc $rpc
         * @return Gateway
         */
        protected function _processRpcQueueItem(
            \Processus\Lib\JsonRpc2\Interfaces\RpcInterface $rpc
        )
        {
            $rpc->setGateway($this);
            $rpc->setGatewayClassName(get_class($this));

            $rpc->setMethod($rpc->getRequest()->getMethod());
            $rpc->setParams($rpc->getRequest()->getParams());
            // rpc.params may be overwritten by crypt module

            $cryptModule = $this->getCryptModule();
            $rpc->setCryptModuleClassName(get_class($cryptModule));
            $rpc->setCryptModule($cryptModule);
            $cryptModule->decryptRequest();

            $authModule = $this->getAuthModule();
            $rpc->setAuthModuleClassName(get_class($authModule));
            $rpc->setAuthModule($authModule);


            $rpcQueue = $this->getRpcQueue();
            $rpcQueue->addItem($rpc);
            $rpcQueue->setCurrentItem($rpc);


            $rpc->getResponse()->setId(
                $rpc->getRequest()->getId()
            );
            $rpc->getResponse()->setVersion(
                $rpc->getRequest()->getVersion()
            );
            $rpc->getResponse()->setJsonrpc(
                $rpc->getRequest()->getJsonrpc()
            );

            $server = $this->getServer();
            $rpc->setServerClassName(get_class($server));
            $server->setRpc($rpc);


            $this->_onBeforeInvokeRpcQueueItem($rpc);

            $server->run();

            $response = $rpc->getResponse();
            if($rpc->hasException()) {

                $response
                    ->setException(
                    $rpc->getException()
                );
                $response->setResult(null);

            } else {

                $response->setResult(
                    $rpc->getResult()
                );
            }

            $this->_onAfterInvokeRpcQueueItem($rpc);

            $rpc->getCryptModule()
                ->encryptResponse();


            return $this;
        }


        // =========== RESPONSE:  ======================

        /**
         * @return null|array
         */
        public function getResponseData()
        {
            return $this->_responseData;
        }



        /**
         * @return array
         */
        public function getResponseHeaders()
        {
            if (!is_array($this->_responseHeaders)) {
                $this->_responseHeaders = array();
            }

            return $this->_responseHeaders;
        }

        /**
         * @param $header string
         * @return Gateway
         */
        public function responseHeadersAddItem($header)
        {
            $headersList = $this->getResponseHeaders();
            $headersList[] = '' . $header;

            $this->_responseHeaders[] = $header;

            return $this;
        }


        // =========== RESPONSE: DATA ======================


        /**
         * @param array $responseData
         * @return string
         */
        protected function _encodeResponseData(
            array $responseData = array()
        ) {
            $result = '';

            $responseText = \Processus\Lib\JsonRpc2\RpcUtil::jsonEncode(
                $responseData, false
            );

            if(!is_string($responseText)) {

                return $result;
            }

            return (string)$responseText;
        }

        /**
         * @param array $responseHeadersList
         * @return Gateway
         */
        protected function _sendResponseHeaders(
            array $responseHeadersList = array()
        ) {

            $result = $this;

            if (count($responseHeadersList) < 1) {

                return $result;
            }


            $connection = $this->getConnection();

            $dict = array();

            foreach ($responseHeadersList as $responseHeader) {
                if (!is_string($responseHeader)) {

                    continue;
                }

                $dictKey = '' . strtolower($responseHeader);
                if (!array_key_exists($dictKey, $dict)) {

                    $connection->writeHeader($responseHeader);

                    $dict[$dictKey] = $responseHeader;
                }

            }

            return $this;
        }

        /**
         * @param $responseText
         * @return Gateway
         */
        protected function _sendResponseText($responseText)
        {
            $responseText = '' . $responseText;

            $connection = $this->getConnection();

            $connection->write($responseText);

            return $this;
        }






        // =========== GATEWAY: RUN ======================


        /**
         * @return Gateway
         * @throws \Exception
         */
        public function run()
        {

            $this->_requireConnection();

            try {

                $this->_requireIsEnabled();

                $this->_requireAuthModule();


                $this->_decodeRequest();

                $this->_validateRequestData();
                $requestData = $this->getRequestData();

                // parse request: is request batched ?
                $batchItemCount = (int)count($requestData);
                $isAssocArray = \Processus\Lib\JsonRpc2\RpcUtil::isAssocArray(
                    $requestData
                );
                $isBatched = (!$isAssocArray);

                $rpcQueue = $this->getRpcQueue();

                $rpcQueue->setIsBatched($isBatched);

                $batch = $requestData;
                if (!$isBatched) {
                    $batch = array(
                        $requestData
                    );
                    $batchItemCount = 1;
                }

                // parse request: check min/maxBatchCount
                $requestBatchMaxItems = (int)$this->getConfigValue(
                    'requestBatchMaxItems'
                );

                if ($batchItemCount < 1) {

                    throw new \Exception(
                        GatewayErrorType::ERROR_GATEWAY_REQUEST_BATCH_IS_EMPTY
                    );

                }
                if ($batchItemCount > $requestBatchMaxItems) {

                    throw new \Exception(
                        GatewayErrorType::ERROR_GATEWAY_REQUEST_BATCH_TOO_LARGE
                    );
                }


                // rpc queue:
                $batchResponse = array();
                foreach ($batch as $rpcDataItem) {

                    if (!is_array($rpcDataItem)) {
                        $rpcDataItem = array();
                    }

                    $rpc = $this->newRpc();


                    $rpc->getRequest()->setData($rpcDataItem);

                    $this->_processRpcQueueItem($rpc);

                    $batchResponseItemData = $this->_getRpcResponseData(
                        $rpc
                    );

                    $batchResponse[] = $batchResponseItemData;

                }

                $responseData = $batchResponse;
                if(!$isBatched) {
                    $responseData = $batchResponse[0];
                }

                $this->_responseData = $responseData;

                $this->_sendResponse();

            } catch (\Exception $e) {

                $this->_onGatewayError($e);
            }

            return $this;
        }


        /**
         * @throws \Exception
         */
        protected function _requireConnection()
        {
            $connection = $this->getConnection();

            if(!$this->hasConnection()) {

                throw new \Exception(
                    GatewayErrorType::ERROR_GATEWAYCONFIG_INVALID_CONNECTION
                );
            }
        }




        protected function _sendResponse()
        {

            $responseData = $this->getResponseData();
            $responseText = $this->_encodeResponseData(
                $responseData
            );

            $responseHeaders = $this->getResponseHeaders();
            if(count($responseHeaders)>0) {
                $this->_sendResponseHeaders($responseHeaders);
            }

            $this->_sendResponseText($responseText);
        }


        /**
         *
         */
        public function init()
        {

            // do fancy stuff here
            // ... set is debugMode?

        }

        /**
         * @throws \Exception
         */
        protected function _requireIsEnabled()
        {
            $isEnabled = ($this->getConfigValue('enabled') === true);
            if (!$isEnabled) {

                throw new \Exception(
                    GatewayErrorType::ERROR_GATEWAY_NOT_ENABLED
                );
            }
        }

        /**
         * @param \Exception $error
         * @return Gateway
         */
        protected function _onGatewayError(\Exception $error)
        {
            $result = $this;


            $isDebugEnabled = $this->getIsDebugEnabled();
            $connection = $this->getConnection();

            $classNameNice = \Processus\Lib\JsonRpc2\RpcUtil::getClassnameNice(
                $this
            );

            $errorMessage = '';

            switch ($error->getMessage()) {

                case GatewayErrorType::ERROR_GATEWAYCONFIG_INVALID_CONNECTION:

                    throw $error;

                    break;

                case GatewayErrorType::ERROR_GATEWAY_NOT_ENABLED:

                    $connection->writeStatus(
                        GatewayStatusType::STATUS_FORBIDDEN
                    );

                    $errorMessage .= 'GATEWAY NOT ENABLED';
                    if ($isDebugEnabled) {
                        $errorMessage .= ' : ' . $classNameNice;
                    }

                    $connection->writeErrorLog(
                        $errorMessage
                    );

                    $connection->writeErrorMessage($errorMessage);

                    break;

                case GatewayErrorType::ERROR_GATEWAY_INVALID_REQUEST:

                    $connection->writeStatus(
                        GatewayStatusType::STATUS_BAD_REQUEST
                    );

                    $errorMessage .= 'INVALID GATEWAY REQUEST';
                    if ($isDebugEnabled) {
                        $errorMessage .= ' : ' . $classNameNice;
                    }

                    $connection->writeErrorLog(
                        $errorMessage
                    );

                    $connection->writeErrorMessage($errorMessage);

                    break;


                case GatewayErrorType::ERROR_GATEWAY_REQUEST_BATCH_IS_EMPTY:

                    $connection->writeStatus(
                        GatewayStatusType::STATUS_BAD_REQUEST
                    );

                    $errorMessage .= 'INVALID GATEWAY REQUEST BATCH IS EMPTY';
                    if ($isDebugEnabled) {
                        $errorMessage .= ' : ' . $classNameNice;
                    }

                    $connection->writeErrorLog(
                        $errorMessage
                    );

                    $connection->writeErrorMessage($errorMessage);

                    break;

                case GatewayErrorType::ERROR_GATEWAY_REQUEST_BATCH_TOO_LARGE:

                    $connection->writeStatus(
                        GatewayStatusType::STATUS_BAD_REQUEST
                    );

                    $errorMessage .= 'INVALID GATEWAY REQUEST BATCH TOO LARGE';
                    if ($isDebugEnabled) {
                        $errorMessage .= ' : ' . $classNameNice;
                    }

                    $connection->writeErrorLog(
                        $errorMessage
                    );

                    $connection->writeErrorMessage($errorMessage);

                    break;

                case GatewayErrorType::ERROR_GATEWAY_INVALID_RESPONSE:

                    $connection->writeStatus(
                        GatewayStatusType::STATUS_INTERNAL_SERVER_ERROR
                    );

                    $errorMessage .= 'INVALID GATEWAY RESPONSE';
                    if ($isDebugEnabled) {
                        $errorMessage .= ' : ' . $classNameNice;
                    }

                    $connection->writeErrorLog(
                        $errorMessage
                    );

                    $connection->writeErrorMessage($errorMessage);

                    break;

                case GatewayErrorType::ERROR_GATEWAYCONFIG_INVALID_SERVER:

                    $connection->writeStatus(
                        GatewayStatusType::STATUS_INTERNAL_SERVER_ERROR
                    );

                    $errorMessage .= 'INVALID GATEWAY CONFIG';
                    if ($isDebugEnabled) {
                        $errorMessage .= ' : ' . $classNameNice;
                        $errorMessage .= ' : Gateway.config.server invalid';
                    }

                    $connection->writeErrorLog(
                        $errorMessage
                    );

                    $connection->writeErrorMessage($errorMessage);

                    break;

                case GatewayErrorType::ERROR_GATEWAYCONFIG_INVALID_AUTHMODULE:

                    $connection->writeStatus(
                        GatewayStatusType::STATUS_INTERNAL_SERVER_ERROR
                    );

                    $errorMessage .= 'INVALID GATEWAY CONFIG';
                    if ($isDebugEnabled) {
                        $errorMessage .= ' : ' . $classNameNice;
                        $errorMessage .= ' : Gateway.config.authModule invalid';
                    }

                    $connection->writeErrorLog(
                        $errorMessage
                    );

                    $connection->writeErrorMessage($errorMessage);

                    break;

                case GatewayErrorType::ERROR_GATEWAY_AUTH_REQUIRED:

                    $connection->writeStatus(
                        GatewayStatusType::STATUS_FORBIDDEN
                    );

                    $errorMessage .= 'AUTHORISATION REQUIRED';
                    if ($isDebugEnabled) {
                        $errorMessage .= ' : ' . $classNameNice;
                        $errorMessage .= ' : gateway.authModule access denied';
                    }

                    $connection->writeErrorLog(
                        $errorMessage
                    );

                    $connection->writeErrorMessage($errorMessage);

                    break;

                default:

                    $connection->writeStatus(
                        GatewayStatusType::STATUS_INTERNAL_SERVER_ERROR
                    );

                    $errorMessage .= 'UNKNOWN GATEWAY ERROR';
                    if ($isDebugEnabled) {
                        $errorMessage .= ' : ' . $classNameNice;
                    }

                    $connection->writeErrorLog(
                        $errorMessage
                    );

                    $connection->writeErrorMessage($errorMessage);

                    break;
            }


            return $result;
        }


        /**
         * @param RPC $rpc
         * @return array
         */
        protected function _getRpcResponseData(RPC $rpc)
        {
            $isDebugEnabled = $this->getIsDebugEnabled();

            $rpcResponse = $rpc->getResponse();


            $responseData = (array)$rpc->getResponse()->getData();

            $responseDataMixin = array(
                'id' => $rpcResponse->getId(),
                'version' => $rpcResponse->getVersion(),
                'jsonrpc' => $rpcResponse->getJsonrpc(),
                'result' => $rpcResponse->getResult(),
                'error' => null,
            );

            foreach($responseDataMixin as $key => $value) {
                $responseData[$key] = $value;
            }

            if (
                ($responseData['version'] === '')
                || ($responseData['version'] === null)
            ) {
                unset($responseData['version']);
            }
            if (
                ($responseData['jsonrpc'] === '')
                || ($responseData['jsonrpc'] === null)
            ) {
                unset($responseData['jsonrpc']);
            }

            if (!$rpcResponse->hasException()) {

                return $responseData;
            }


            $rpcException = $rpcResponse->getException();

            $error = array(
                'message' => $rpcException->getMessage(),
                'class' => \Processus\Lib\JsonRpc2\RpcUtil::getClassnameNice(
                    $rpcException
                ),
                'gateway' => \Processus\Lib\JsonRpc2\RpcUtil::getClassnameNice(
                    $this
                ),
                'server' => \Processus\Lib\JsonRpc2\RpcUtil::getClassnameNice(
                    $this->getServer()
                ),
                'code' => $rpcException->getCode(),
                'file' => $rpcException->getFile(),
                'line' => $rpcException->getLine(),
                'stackTrace' => $rpcException->getTraceAsString(),
            );

            if (!$isDebugEnabled) {
                $unsetKeys = array(
                    'code',
                    'file',
                    'line',
                    'stackTrace',
                    'class',
                    'gateway',
                    'server',
                );
                foreach ($unsetKeys as $key) {
                    unset($error[$key]);
                }

                $error['message'] = 'AN ERROR OCCURRED';
            }

            $responseData['result'] = null;
            $responseData['error'] = $error;


            return $responseData;

        }









        // =========== PROCESSUS: MISC ======================





        /**
         * @return Gateway|\Processus\Lib\JsonRpc2\Interfaces\GatewayInterface
         */
        public function unsetRpcQueue()
        {
            $this->_rpcQueue = array();

            return $this;
        }


        /**
         * @param Interfaces\CryptModuleInterface $cryptModule
         * @return Gateway|Interfaces\GatewayInterface
         */
        public function setCryptModule(
            Interfaces\CryptModuleInterface $cryptModule
        )
        {
            $this->_cryptModule = $cryptModule;

            return $this;
        }

        /**
         * @return Gateway|Interfaces\GatewayInterface
         */
        public function unsetCryptModule()
        {
            $this->_cryptModule = null;

            return $this;
        }

        /**
         * @return Interfaces\CryptModuleInterface
         */
        public function getCryptModule()
        {
            if(!$this->hasCryptModule()) {

                $this->_cryptModule = $this->newCryptModule();

            }

            return $this->_cryptModule;
        }

        /**
         * @return bool
         */
        public function hasCryptModule()
        {
            return (
                $this->_cryptModule
                    instanceof Interfaces\CryptModuleInterface
            );
        }

        /**
         * @return Interfaces\CryptModuleInterface
         */
        public function newCryptModule()
        {

            // 'cryptModuleClassName' => '{{NAMESPACE}}\\CryptModule',
            $cryptModuleClassName = '' . $this->getConfigValue(
                'cryptModuleClassName'
            );

           if(empty($cryptModuleClassName)) {
               throw new \Exception(
                   GatewayErrorType::ERROR_GATEWAYCONFIG_INVALID_CRYPTMODULE
               );
           }

            $cryptModuleInstance = RpcUtil::newClassInstanceFromTemplateString(
                $this,
                $cryptModuleClassName,
                array(),
                false
            );

            if(!
            ($cryptModuleInstance instanceof
                \Processus\Lib\JsonRpc2\Interfaces\CryptModuleInterface)
            ) {
                throw new \Exception(
                    GatewayErrorType::ERROR_GATEWAYCONFIG_INVALID_CRYPTMODULE
                );
            }

            return $cryptModuleInstance;

        }





        /**
         * @param Interfaces\AuthModuleInterface $authModule
         * @return Gateway|Interfaces\GatewayInterface
         */
        public function setAuthModule(
            Interfaces\AuthModuleInterface $authModule
        )
        {
            $this->_authModule = $authModule;

            return $this;
        }

        /**
         * @return Gateway|Interfaces\GatewayInterface
         */
        public function unsetAuthModule()
        {
            $this->_authModule = null;

            return $this;
        }

        /**
         * @return Interfaces\AuthModuleInterface
         */
        public function getAuthModule()
        {
            if(!$this->hasAuthModule()) {

                $this->_authModule = $this->newAuthModule();

            }

            return $this->_authModule;
        }

        /**
         * @return bool
         */
        public function hasAuthModule()
        {
            return (
                $this->_authModule
                    instanceof Interfaces\AuthModuleInterface
            );
        }

        /**
         * @return Interfaces\AuthModuleInterface
         */
        public function newAuthModule()
        {

            $authModuleClassName = '' . $this->getConfigValue(
                'authModuleClassName'
            );

            if(empty($authModuleClassName)) {
                throw new \Exception(
                    GatewayErrorType::ERROR_GATEWAYCONFIG_INVALID_AUTHMODULE
                );
            }

            $authModuleInstance = RpcUtil::newClassInstanceFromTemplateString(
                $this,
                $authModuleClassName,
                array(),
                false
            );

            if(!
            ($authModuleInstance instanceof
                \Processus\Lib\JsonRpc2\Interfaces\AuthModuleInterface)
            ) {
                throw new \Exception(
                    GatewayErrorType::ERROR_GATEWAYCONFIG_INVALID_AUTHMODULE
                );
            }

            return $authModuleInstance;

        }


        /**
         * @return Gateway
         * @throws \Exception
         */
        protected function _requireAuthModule()
        {
            $authModule = $this->getAuthModule();

            if (!(
                $authModule
                    instanceof
                    Interfaces\AuthModuleInterface
            )) {

                throw new \Exception(
                    GatewayErrorType::ERROR_GATEWAYCONFIG_INVALID_AUTHMODULE
                );
            }

            return $this;
        }





    }


