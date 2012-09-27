<?php
namespace Application\JsonRpc2\V1\Seb;


class Auth
    extends
    \Processus\Lib\JsonRpc2\Auth
{

    public function isAuthorized()
    {

        return true;
    }
}