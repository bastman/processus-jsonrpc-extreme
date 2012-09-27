<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface RpcRequestVoInterface
    extends BaseVoInterface
{



    /**
     * @param $id string|int|float|null
     * @return RpcRequestVoInterface
     */
    public function setId($id);


    /**
     * @return RpcRequestVoInterface
     */
    public function unsetId();

    /**
     * @return string|int|float|null
     */
    public function getId();


    /**
     * @return bool
     */
    public function hasId();


    /**
     * @param $version string
     * @return RpcRequestVoInterface
     */
    public function setVersion($version);


    /**
     * @return RpcRequestVoInterface
     */
    public function unsetVersion();

    /**
     * used by jsonrpc v1
     * @return string
     */
    public function getVersion();


    /**
     * @return bool
     */
    public function hasVersion();


    /**
     * @param $jsonrpc string
     * @return RpcRequestVoInterface
     */
    public function setJsonrpc($jsonrpc);

    /**
     * @return RpcRequestVoInterface
     */
    public function unsetJsonrpc();


    /**
     * used by jsonrpc v2
     * @return string
     */
    public function getJsonrpc();


    /**
     * @return bool
     */
    public function hasJsonrpc();


    /**
     * @return string
     */
    public function getMethod();



    /**
     * @param $method string
     * @return RpcRequestVoInterface
     */
    public function setMethod($method);


    /**
     * @return RpcRequestVoInterface
     */
    public function unsetMethod();


    /**
     * @return bool
     */
    public function hasMethod();


    /**
     * @return array
     */
    public function getParams();


    /**
     * @param array $params
     * @return RpcRequestVoInterface
     */
    public function setParams($params = array());


    /**
     * @return RpcRequestVoInterface
     */
    public function unsetParams();


    /**
     * @return bool
     */
    public function hasParams();



}
