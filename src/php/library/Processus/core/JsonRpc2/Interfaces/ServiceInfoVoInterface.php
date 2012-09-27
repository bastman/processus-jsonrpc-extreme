<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface ServiceInfoVoInterface
    extends BaseVoInterface
{



    /**
     * @return string
     */
    public function getServiceUid();





    /**
     * @return string
     */
    public function getClassName();


    /**
     * @param string $className
     * @return ServiceInfoVoInterface
     */
    public function setClassName($className);


    /**
     * @return string
     */
    public function getServiceName();


    /**
     * @return string
     * @return ServiceInfoVoInterface
     */
    public function setServiceName($serviceName);


    /**
     * @return bool
     */
    public function getIsValidateMethodParamsEnabled();


    /**
     * @param bool $value
     * @return ServiceInfoVoInterface
     */
    public function setIsValidateMethodParamsEnabled($value);


    /**
     * @return array
     */
    public function getClassMethodFilter();

    /**
     * @param array $filter
     * @return ServiceInfoVoInterface
     */
    public function setClassMethodFilter($filter = array());

    /**
     * @param string $filterkey
     * @return array
     */
    public function getClassMethodFilterKey($filterKey);



    /**
     * @return array
     */
    public function getClassMethodFilterAllow();


    /**
     * @return array
     */
    public function getClassMethodFilterDeny();


}
