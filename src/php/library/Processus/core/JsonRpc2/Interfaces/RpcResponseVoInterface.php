<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface RpcResponseVoInterface
    extends BaseVoInterface
{




    /**
     * @param $id string|int|float|null
     * @return RpcResponseVoInterface
     */
    public function setId($id);


    /**
     * @return RpcResponseVoInterface
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
     * @return RpcResponseVoInterface
     */
    public function setVersion($version);


    /**
     * @return RpcResponseVoInterface
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
     * @return RpcResponseVoInterface
     */
    public function setJsonrpc($jsonrpc);

    /**
     * @return RpcResponseVoInterface
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
     * @param $result mixed
     * @return RpcResponseVoInterface
     */
    public function setResult($result);


    /**
     * @return mixed
     */
    public function getResult();


    /**
     * @return RpcResponseVoInterface
     */
    public function unsetResult();



    /**
     * @param $error array
     * @return RpcResponseVoInterface
     */
    public function setError($error);

    /**
     * @return RpcResponseVoInterface
     */
    public function unsetError();

    /**
     * @return array|null
     */
    public function getError();


    /**
     * @return bool
     */
    public function hasError();




    /**
     * @param \Exception $exception
     * @return RpcResponseVoInterface
     */
    public function setException(\Exception $exception);

    /**
     * @return RpcResponseVoInterface
     */
    public function unsetException();


    /**
     * @return \Exception|null
     */
    public function getException();


    /**
     * @return bool
     */
    public function hasException();



}
