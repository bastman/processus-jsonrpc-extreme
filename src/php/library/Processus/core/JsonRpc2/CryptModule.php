<?php

namespace Processus\Lib\JsonRpc2;

class CryptModule
    implements
    \Processus\Lib\JsonRpc2\Interfaces\CryptModuleInterface
{

    /**
     * @var Interfaces\RpcInterface
     */
    protected $_rpc;

    /**
     * @param Interfaces\RpcInterface $rpc
     * @return CryptModule|Interfaces\CryptModuleInterface
     */
    public function setRpc(
        Interfaces\RpcInterface $rpc
    )
    {
        $this->_rpc = $rpc;

        return $this;
    }

    /**
     * @return CryptModule|Interfaces\CryptModuleInterface
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
     * @return CryptModule|Interfaces\CryptModuleInterface
     */
    public function decryptRequest()
    {

        return $this;
    }

    /**
     * @return CryptModule|Interfaces\CryptModuleInterface
     */
    public function encryptResponse()
    {

        return $this;
    }

    /**
     * @return CryptModule|Interfaces\CryptModuleInterface
     */
    public function signResponse()
    {
        $result = $this;

        $secret = 'secret';


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
            $secret,
            'HMAC-SHA256',
            time()
        );
        $rpc->getResponse()
            ->setDataKey('signature', $signature);

        return $result;
    }


    /**
     * @return CryptModule|Interfaces\CryptModuleInterface
     */
    public function validateRequestSignature()
    {
        $result = $this;

        $secret = 'secret';

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
            $secret,
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
