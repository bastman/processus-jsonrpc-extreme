<?php
namespace Application\JsonRpc2\V1\Seb;


    class DispatcherZmq
        extends
        \Processus\Lib\JsonRpc2\Dispatcher
    {

        public function run()
        {
            // bin seb$ php speedy.php "Zmq\ZmqConnectionTest"

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

            return $this;
        }





    }


