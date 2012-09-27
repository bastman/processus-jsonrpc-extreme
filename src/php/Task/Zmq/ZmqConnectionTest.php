<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seb
 * Date: 9/25/12
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Application\Task\Zmq;
class ZmqConnectionTest extends \Processus\Abstracts\AbstractTask
{

    public function run()
    {
        /* Create new queue object */
        $queue = new \ZMQSocket(new \ZMQContext(), \ZMQ::SOCKET_PUSH, "MySock1");
        $queue->connect("tcp://127.0.0.1:5555");

        $rpcData = array(
            "id"        => 1,
            "params" =>array(),
            /**
            "params"    => array(array(
                "message"  => "Seb.Test.ping",
                "created"  => time(),
                "someData" => mt_rand(0, 49344409875093475),
            )),
             * **/
            "method"    => "Seb.Test.ping",
            //"extended"  => array(),
        );

        $rpcBatch = array();
        for($i=0;$i<2;$i++) {
            $rpcData = (array)$rpcData;
            $rpcData['id'] = $i;
            $rpcBatch[]=$rpcData;
        }
        $mqData = $rpcBatch;

       // {"method":"Seb.Test.ping", "id":"1", "version":"1.1", "jsonrpc":"1.1", "params":[]}
        /* Assign socket 1 to the queue, send and receive */
        $queue->send(json_encode($mqData), \ZMQ::MODE_NOBLOCK);
        //$queue->send(json_encode($mqData), \ZMQ::MODE_NOBLOCK);
        //$queue->send(json_encode($mqData), \ZMQ::MODE_NOBLOCK);
        //$queue->send(json_encode($mqData), \ZMQ::MODE_NOBLOCK);

    }

    /**
     * @return string
     */
    protected function _getLogTable()
    {
        // TODO: Implement _getLogTable() method.
    }

    /**
     * @param $rawObject
     * @return array
     */
    protected function _getSqlLogParams($rawObject)
    {
        // TODO: Implement _getSqlLogParams() method.
    }
}
