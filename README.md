OVERVIEW
========

(1) IN: raw request (via http, zeromq, etc) => gateway (protocol handler: fetch, parse, decrypt, check auth) 

(2) gateway == RPC => server (router / api) => service

(3) service == result/exception => server 

(4) server  == RPC (result,exception) => gateway

(5) gateway (protocol handler: create response, crypt & sign it) == RAW RESPONSE => OUT (via http, zeromq, etc)






JSON-RPC VIA HTTP
=================

    $gateway = new Gateway();
    $gateway->init();
    $gateway->setIsDebugEnabled(true);
    
    $connection = new ConnectionHttp();
    $gateway->setConnection($connection);
    
    $gateway->run();
    
PROFILING MOCKED JSON-RPC VIA HTTP
==================================
    
    $requestData = array(
        'method' => 'Seb.Test.ping',
    );
    $requestText = json_encode($requestData);
    
    $startTs = microtime(true);

    for($i=0; $i<1;$i++) {
        //var_dump($i);
        $gateway = new Gateway();
        $gateway->init();
        $gateway->setIsDebugEnabled(true);
    
        $connection = new ConnectionHttp();
        $gateway->setConnection($connection);
    
        $connection->setIsInputBufferEnabled(true);
        $connection->setInputBuffer($requestText);
    
        $gateway->run();
    
        unset($gateway);
    }

    $endTs = microtime(true);
    
    var_dump(__METHOD__);
    var_dump('ITERATIONS: '.$i);
    var_dump('MICROTIME :');
    $totalTime=$endTs-$startTs;
    
    var_dump($totalTime);
    
    var_dump('MICROTIME (per loop):');
    
    var_dump($totalTime/$i);



JSON-RPC VIA ZEROMQ
===================

    $connection = new ConnectionZmq();
    $connection->requireZmq();
    
    
    $gateway = null;
    
    $socket = new \ZMQSocket(new \ZMQContext(), \ZMQ::SOCKET_PULL);
    $socket->bind("tcp://0.0.0.0:5555");
    
    /* Loop receiving and echoing back */
    while (true) {
        echo 1;
        usleep(10000);
        echo 2;
        try {
    
            $message = '' . $socket->recv(\ZMQ::MODE_NOBLOCK);
    
            if (empty($message)) {
    
                continue;
            }
    
            $connection = new ConnectionZmq();
            $connection->setSocket($socket);
            $connection->setSocketReadMode(\ZMQ::MODE_NOBLOCK);
            $connection->unsetInputBuffer();
            $gateway = new Gateway();
            $gateway->init();
            $gateway->setIsDebugEnabled(true);
            $gateway->setConnection($connection);
    
            $connection->setInputBuffer($message);
            $connection->setIsInputBufferEnabled(true);
    
            $gateway->run();
    
    
            unset($connection);
            unset($gateway);
    
        } catch (\Exception $error) {
            //$logManager = new \Application\Manager\LoggingManager();
            //$logManager->logDump($error, "logging:error:");
            //var_dump($error);
    
            throw $error;
        }
        //
    
        if (isset($gateway)) {
            unset($gateway);
        }
    
        if (isset($connection)) {
            unset($connection);
        }
    
    
    }





