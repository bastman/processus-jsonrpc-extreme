<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface GatewayInterface
{

    /**
     * @return GatewayInterface
     */
    public function init();

    /**
     * @return GatewayInterface
     */
    public function run();

    /**
     * @param $server ServerInterface
     * @return GatewayInterface
     */
    public function setServer(ServerInterface $server);

    /**
     * @return GatewayInterface
     */
    public function unsetServer();
    /**
     *
     * @return ServerInterface
     */
    public function getServer();

    /**
     * @return bool
     */
    public function hasServer();

    /**
     * @return ServerInterface
     */
    public function newServer();


    /**
     * @return RpcQueueInterface
     */
    public function getRpcQueue();
    /**
     * @return GatewayInterface
     */
    public function unsetRpcQueue();






    /**
     * @param $cryptModule CryptModuleInterface
     * @return GatewayInterface
     */
    public function setCryptModule(CryptModuleInterface $cryptModule);

    /**
     * @return GatewayInterface
     */
    public function unsetCryptModule();
    /**
     *
     * @return CryptModuleInterface
     */
    public function getCryptModule();

    /**
     * @return bool
     */
    public function hasCryptModule();

    /**
     * @return CryptModuleInterface
     */
    public function newCryptModule();





}
