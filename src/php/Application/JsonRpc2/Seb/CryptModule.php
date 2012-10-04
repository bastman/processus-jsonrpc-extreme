<?php
namespace Application\JsonRpc2\V1\Seb;


class CryptModule
    extends
    \Processus\Lib\JsonRpc2\ProcsUs\CryptModule
{




    private $_cryptEnabled = false;
    private $_verifyRequestSignatureEnabled = false;
    private $_signResponseEnabled = true;



    /**
     * @var string
     */
    private $_signatureSecret = 'secret';


    /**
     * @return CryptModule|\Processus\Lib\JsonRpc2\CryptModule|\Processus\Lib\JsonRpc2\Interfaces\CryptModuleInterface|\Processus\Lib\JsonRpc2\ProcsUs\CryptModule
     */
    public function decryptRequest()
    {
        if($this->_cryptEnabled) {

            return parent::decryptRequest();
        }

        return $this;

    }


    /**
     * @return CryptModule|\Processus\Lib\JsonRpc2\CryptModule|\Processus\Lib\JsonRpc2\Interfaces\CryptModuleInterface|\Processus\Lib\JsonRpc2\ProcsUs\CryptModule
     */
    public function encryptResponse()
    {
        if($this->_cryptEnabled) {

            return parent::encryptResponse();
        }

        return $this;

    }


    /**
     * @return CryptModule
     */
    public function signResponse()
    {

        $result = $this;

        if(!$this->_signResponseEnabled) {

            return $result;
        }

        $rpc = $this->getRpc();

        $rpcResult = $rpc->getResult();

        $signature = \Processus\Lib\JsonRpc2\RpcUtil::
            createRequestSignature(
                array(
                    'result' =>$rpcResult,
                ),
                array(
                    'result'
                ),
                $this->_signatureSecret,
                'HMAC-SHA256',
                time()
            );

        $rpc->getResponse()
            ->setDataKey('signature', $signature);

        return $result;
    }


    /**
     * @return CryptModule
     * @throws \Exception
     */
    public function validateRequestSignature()
    {
        $result = $this;

        if(!$this->_verifyRequestSignatureEnabled) {

            return $result;
        }

        $rpc = $this->getRpc();

        $sigData = array(
            'method' => $rpc->getRequest()
                ->getDataKey('method'),
            'params' => $rpc->getRequest()
            ->getDataKey('params'),
        );
        $sigKeys = array_keys($sigData);

        $signature = $rpc->getRequest()
            ->getDataKey('signature');

        $signatureIsValid = \Processus\Lib\JsonRpc2\RpcUtil::
            validateSignedRequest(
                $signature,
                $sigData,
                $sigKeys,
                $this->_signatureSecret,
                'HMAC-SHA256'
            );

        if(!$signatureIsValid) {

            throw new \Exception(
                'Invalid rpc.signature '.get_class($this).__METHOD__
            );
        }

        return $result;
    }






}