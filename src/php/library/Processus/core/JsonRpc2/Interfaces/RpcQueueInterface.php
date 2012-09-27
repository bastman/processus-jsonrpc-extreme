<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface RpcQueueInterface
{

    /**
     * @param RpcInterface $rpc
     * @return RpcQueueInterface
     */
    public function addItem(RpcInterface $rpc);


    /**
     * @return array
     */
    public function getItems();


    /**
     * @return RpcQueueInterface
     */
    public function unsetItems();

    /**
     * @param bool $isBatched
     * @return RpcQueueInterface
     */
    public function setIsBatched($isBatched);


    /**
     * @return bool
     */
    public function getIsBatched();



    /**
     * @param RpcInterface $rpc
     * @return RpcQueueInterface
     */
    public function setCurrentItem(RpcInterface $rpc);


    public function unsetCurrentItem();


    /**
     * @return RpcInterface|null
     */
    public function getCurrentItem();


    /**
     * @return bool
     */
    public function hasCurrentItem();



}
