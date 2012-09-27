<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface BaseVoInterface
{



    /**
     * @param array $data
     * @return BaseVoInterface
     */
    public function setData($data = array());

    /**
     * @return array
     */
    public function getData();

    /**
     * @return BaseVoInterface
     */
    public function unsetData();


    /**
     * @param array $mixin
     * @return BaseVoInterface
     */
    public function mixinData($mixin = array());

    /**
     * @param $key
     * @return mixed
     */
    public function getDataKey($key);


    /**
     * @param $key
     * @param $value
     * @return BaseVoInterface
     */
    public function setDataKey($key, $value);


    /**
     * @param $key
     * @return bool
     */
    public function hasDataKey($key);


    /**
     * @param $key string
     * @return BaseVoInterface
     */
    public function unsetDataKey($key);


    /**
     * @param array $keysList
     * @return BaseVoInterface
     */
    public function unsetDataKeys($keysList = array());


    /**
     * @param array $dictionary
     * @return BaseVoInterface
     */
    public function ensureData(
        $dictionary = array()
    );




}
