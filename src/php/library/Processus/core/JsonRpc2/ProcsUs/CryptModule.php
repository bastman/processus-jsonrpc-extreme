<?php

namespace Processus\Lib\JsonRpc2\ProcsUs;

    class CryptModule
        extends
        \Processus\Lib\JsonRpc2\CryptModule

    {


        /**
         * @return \Processus\Lib\JsonRpc2\CryptModule|\Processus\Lib\JsonRpc2\Interfaces\CryptModuleInterface|CryptModule
         * @throws \Exception
         */
        public function decryptRequest()
        {
            $result = $this;

            //parent::decryptRequest();

            $rpc = $this->getRpc();

            // Decryption settings
            $encryptionSettings = \Application\ApplicationContext::getInstance()
                ->getGameSettings()
                ->getEncryption()
            ;

            $encryptionEnabled = ($encryptionSettings['enabled'] === true);
            if(!$encryptionEnabled) {

                return $result;
            }

            $rpcParamsJson = $rpc->getRequest()
                ->getDataKey('params');
            $rpcParamsJson = \Application\ApplicationContext::getInstance()
                ->getCrypt()
                ->decrypt($rpcParamsJson);

            $rpcParams = \Processus\Lib\JsonRpc2\RpcUtil::jsonDecode(
                $rpcParamsJson, true, false
            );
            if(!is_array($rpcParams)) {

                throw new \Exception(
                    'Invalid JSON-RPC request (decryption failed)'
                );
            }

            $rpc->getRequest()
                ->setDataKey('params', $rpcParams);
            $rpc->setParams($rpcParams);

            return $result;

        }


        /**
         * @return \Processus\Lib\JsonRpc2\CryptModule|\Processus\Lib\JsonRpc2\Interfaces\CryptModuleInterface|CryptModule
         * @throws \Exception
         */
        public function encryptResponse()
        {
            //parent::encryptResponse();

            $result = $this;

            $rpc = $this->getRpc();

            // Decryption settings
            $encryptionSettings = \Application\ApplicationContext::getInstance()
                ->getGameSettings()
                ->getEncryption()
            ;

            $encryptionEnabled = ($encryptionSettings['enabled'] === true);

            if(!$encryptionEnabled) {

                return $result;
            }


            $rpcResult = $rpc->getResult();
            $rpcResultJson = \Processus\Lib\JsonRpc2\RpcUtil::jsonEncode(
                $rpcResult, false
            );
            if(!is_string($rpcResultJson)) {

                throw new \Exception(
                    'Invalid JSON-RPC resonse.result (encryption failed)'
                );
            }

            $rpcResultJson = \Application\ApplicationContext::getInstance()
                ->getCrypt()
                ->encrypt($rpcResultJson);

            $rpc->getResponse()
                ->setResult($rpcResultJson);


            return $result;
        }
    }


