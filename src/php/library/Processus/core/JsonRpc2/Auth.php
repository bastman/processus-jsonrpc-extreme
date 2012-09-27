<?php

namespace Processus\Lib\JsonRpc2;

class Auth
    implements
    \Processus\Lib\JsonRpc2\Interfaces\AuthInterface
{
    /**
     * @var bool
     */
    private $_isAuthorized = true;

    /**
     * @return bool
     */
    public function isAuthorized()
    {
        return ($this->_isAuthorized===true);
    }

    public function setAuthData($authData)
    {
        return true;
    }
}
