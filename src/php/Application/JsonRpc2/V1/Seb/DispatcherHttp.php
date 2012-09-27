<?php
namespace Application\JsonRpc2\V1\Seb;


    class DispatcherHttp
        extends
        \Processus\Lib\JsonRpc2\Dispatcher
    {

        public function run()
        {

            $gateway = new Gateway();
            $gateway->init();
            $gateway->setIsDebugEnabled(true);

            $connection = new ConnectionHttp();
            $gateway->setConnection($connection);
            $gateway->run();



        }

    }


