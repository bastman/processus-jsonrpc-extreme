<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface RpcInterface
{

    /**
     * @return RpcRequestVoInterface
     */
    public function newRequest();


    /**
     * @return RpcRequestVoInterface
     */
    public function getRequest();


    /**
     * @return bool
     */
    public function hasRequest();


    /**
     * @param RpcRequestVoInterface $request
     * @return RpcInterface
     */
    public function setRequest(
        RpcRequestVoInterface $request
    );


    /**
     * @return RpcInterface
     */
    public function unsetRequest();



    /**
     * @return RpcResponseVoInterface
     */
    public function newResponse();

    /**
     * @return RpcResponseVoInterface
     */
    public function getResponse();

    /**
     * @return bool
     */
    public function hasResponse();


    /**
     * @param RpcResponseVoInterface $response
     * @return RpcInterface
     */
    public function setResponse(
        RpcResponseVoInterface $response
    );


    /**
     * @return string
     */
    public function getServiceClassName();

    /**
     * @param string $className
     * @return RpcInterface
     */
    public function setServiceClassName($className);

    /**
     * @return bool
     */
    public function hasServiceClassName();



    /**
     * @return string
     */
    public function getServiceMethodName();

    /**
     * @param string $methodName
     * @return RpcInterface
     */
    public function setServiceMethodName($methodName);

    /**
     * @return bool
     */
    public function hasServiceMethodName();



    /**
     * @return \ReflectionClass
     */
    public function getServiceReflectionClass();

    /**
     * @param \ReflectionClass $reflectionClass
     * @return RpcInterface
     */
    public function setServiceReflectionClass(
        \ReflectionClass $reflectionClass
    );

    /**
     * @return bool
     */
    public function hasServiceReflectionClass();



    /**
     * @return \ReflectionMethod
     */
    public function getServiceReflectionMethod();

    /**
     * @param \ReflectionMethod $reflectionClass
     * @return RpcInterface
     */
    public function setServiceReflectionMethod(
        \ReflectionMethod $reflectionMethod
    );

    /**
     * @return bool
     */
    public function hasServiceReflectionMethod();


    /**
     * @param ServiceInterface $serviceInstance
     * @return RpcInterface
     */
    public function setService(ServiceInterface $serviceInstance);
    /**
     * @return RpcInterface
     */
    public function unsetService();
    /**
     * @return bool
     */
    public function hasService();

    /**
     * @return ServiceInterface|null
     */
    public function getService();

    /**
     * @param array $args
     * @return RpcInterface
     */
    public function setServiceMethodArgs($args = array());

    /**
     * @return array
     */
    public function getServiceMethodArgs();

    /**
     * @param string $gatewayClassName
     * @return RpcInterface
     */
    public function setGatewayClassName($gatewayClassName);

    /**
     * @return string
     */
    public function getGatewayClassName();

    /**
     * @param GatewayInterface $gateway
     * @return RpcInterface
     */
    public function setGateway(GatewayInterface $gateway);

    /**
     * @return RpcInterface
     */
    public function unsetGateway();

    /**
     * @return bool
     */
    public function hasGateway();

    /**
     * @param string $serverClassName
     * @return RpcInterface
     */
    public function setServerClassName($serverClassName);

    /**
     * @return string
     */
    public function getServerClassName();

    /**
     * @param ServerInterface $server
     * @return RpcInterface
     */
    public function setServer(ServerInterface $server);

    /**
     * @return RpcInterface
     */
    public function unsetServer();

    /**
     * @return bool
     */
    public function hasServer();
    /**
     * @param ServiceInfoVoInterface $serviceInfo
     * @return RpcInterface
     */



    /**
     * @param string $authModuleClassName
     * @return RpcInterface
     */
    public function setAuthModuleClassName($authModuleClassName);

    /**
     * @return string
     */
    public function getAuthModuleClassName();

    /**
     * @param AuthModuleInterface $authModule
     * @return RpcInterface
     */
    public function setAuthModule(AuthModuleInterface $authModule);
    /**
     * @return AuthModuleInterface|null
     */
    public function getAuthModule();
    /**
     * @return RpcInterface
     */
    public function unsetAuthModule();

    /**
     * @return bool
     */
    public function hasAuthModule();



    /**
     * @param string $cryptModuleClassName
     * @return RpcInterface
     */
    public function setCryptModuleClassName($cryptModuleClassName);

    /**
     * @return string
     */
    public function getCryptModuleClassName();

    /**
     * @param CryptModuleInterface $cryptModule
     * @return RpcInterface
     */
    public function setCryptModule(CryptModuleInterface $cryptModule);
    /**
     * @return CryptModuleInterface|null
     */
    public function getCryptModule();
    /**
     * @return RpcInterface
     */
    public function unsetCryptModule();

    /**
     * @return bool
     */
    public function hasCryptModule();



    /**
     * @param ServiceInfoVoInterface $serviceInfo
     * @return RpcInterface
     */



    public function setServiceInfo(ServiceInfoVoInterface $serviceInfo);
    /**
     * @return RpcInterface
     */
    public function unsetServiceInfo();

    /**
     * @return ServiceInfoVoInterface
     */
    public function getServiceInfo();
    /**
     * @return bool
     */
    public function hasServiceInfo();




    /**
     * @param $result
     * @return RpcInterface
     */
    public function setResult($result);


    /**
     * @return mixed
     */
    public function getResult();


    /**
     * @param \Exception $exception
     * @return RpcInterface
     */
    public function setException(\Exception $exception);

    /**
     * @return RpcInterface
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



    /**
     * @param string $method
     * @return RpcInterface
     */
    public function setMethod($method);


    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return bool
     */
    public function hasMethod();

    /**
     * @param array $params
     * @return RpcInterface
     */
    public function setParams($params = array());


    /**
     * @return array
     */
    public function getParams();


    /**
     * @param array|mixed|null $authData
     * @return RpcInterface
     */
    public function setAuthData($authData);


    /**
     * @return array|mixed|null
     */
    public function getAuthData();




}
