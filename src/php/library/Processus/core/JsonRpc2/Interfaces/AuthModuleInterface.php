<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface AuthModuleInterface
{

    /**
     * @param RpcInterface $rpc
     * @return AuthModuleInterface
     */
    public function setRpc(RpcInterface $rpc);

    /**
     * @return AuthModuleInterface
     */
    public function unsetRpc();
    /**
     * @return RpcInterface
     */
    public function getRpc();

    /**
     * @return bool
     */
    public function hasRpc();


    /**
     * @return bool
     */
    public function isAuthorized();





}
