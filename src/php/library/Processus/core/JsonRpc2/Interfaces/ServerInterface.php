<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface ServerInterface
{




    /**
     * @param GatewayInterface $gateway
     * @return ServerInterface
     */
    public function setGateway(GatewayInterface $gateway);

    /**
     * @return ServerInterface
     */
    public function unsetGateway();

    /**
     * @return GatewayInterface|null
     */
    public function getGateway();

    /**
     * @return bool
     */
    public function hasGateway();


    /**
     * @param RpcInterface $rpc
     * @return ServerInterface
     */
    public function setRpc(RpcInterface $rpc);
    /**
     * @return ServerInterface
     */
    public function unsetRpc();
    /**
     * @return RpcInterface|null
     */
    public function getRpc();

    /**
     * @return bool
     */
    public function hasRpc();
    /**
     * @return ServerInterface
     * @throws \Exception
     */
    public function run();

}
