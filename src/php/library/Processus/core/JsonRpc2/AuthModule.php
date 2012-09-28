<?php

namespace Processus\Lib\JsonRpc2;

class AuthModule
    implements
    \Processus\Lib\JsonRpc2\Interfaces\AuthModuleInterface
{
    /**
     * @var bool
     */
    private $_isAuthorized = true;

    /**
     * @var Interfaces\RpcInterface|null
     */
    protected $_rpc;


    /**
     * @return bool
     */
    public function isAuthorized()
    {
        return ($this->_isAuthorized===true);
    }


    /**
     * @param Interfaces\RpcInterface $rpc
     * @return Auth|Interfaces\AuthModuleInterface
     */
    public function setRpc(Interfaces\RpcInterface $rpc)
    {
        $this->_rpc = $rpc;

        return $this;
    }

    /**
     * @return Auth|Interfaces\AuthModuleInterface
     */
    public function unsetRpc()
    {
        $this->_rpc = null;

        return $this;
    }

    /**
     * @return null|Interfaces\RpcInterface
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
            $this->_rpc instanceof Interfaces\RpcInterface
        );
    }
}
