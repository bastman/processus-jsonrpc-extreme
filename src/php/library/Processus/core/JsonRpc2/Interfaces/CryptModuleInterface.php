<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface CryptModuleInterface
{

    /**
     * @param RpcInterface $rpc
     * @return CryptModuleInterface
     */
    public function setRpc(RpcInterface $rpc);

    /**
     * @return CryptModuleInterface
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
     * @return CryptModuleInterface
     */
    public function decryptRequest();

    /**
     * @return CryptModuleInterface
     */
    public function encryptResponse();

    /**
     * @return CryptModuleInterface
     */
    public function signResponse();

    /**
     * @return CryptModuleInterface
     */
    public function validateRequestSignature();



}
