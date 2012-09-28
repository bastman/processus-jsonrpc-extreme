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
}
