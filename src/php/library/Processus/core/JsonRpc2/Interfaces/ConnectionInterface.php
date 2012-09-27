<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/20/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\JsonRpc2\Interfaces;

interface ConnectionInterface
{



    /**
     * @param GatewayInterface $gateway
     * @return ConnectionInterface
     */
    public function setGateway(
        GatewayInterface $gateway
    );
    /**
     * @return ConnectionInterface
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
     * @param $text string
     * @return ConnectionInterface
     */
    public function write($text);

    /**
     * @return string
     */
        public function read();



    /**
     * @param $header string
     * @return ConnectionInterface
     */
    public function writeHeader($header);


    /**
     * @param $status string
     * @return ConnectionInterface
     */
    public function writeStatus($status);

    /**
     * @param $message string
     * @return ConnectionInterface
     */
    public function writeErrorLog($message);

    /**
     * @param $message string
     * @return ConnectionInterface
     */
    public function writeErrorMessage($message);




}
