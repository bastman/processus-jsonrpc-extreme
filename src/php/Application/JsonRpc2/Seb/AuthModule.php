<?php
namespace Application\JsonRpc2\V1\Seb;


class AuthModule
    extends
    \Processus\Lib\JsonRpc2\AuthModule
{

    public function isAuthorized()
    {

return true;

        $fbAuth = \Processus\Lib\JsonRpc2\ProcsUs\Util::
            newProcessusFacebookAuth();

        $rpc = $this->getRpc();



        $authData = $rpc->getAuthData();
        if(!is_array($authData)) {

            // do sth., eg fetch from rpc.request

            $rpc->setAuthData($authData);
        }


        $fbAuth->setAuthData($authData);

        return $fbAuth->isAuthorized();


        return true;
    }

}