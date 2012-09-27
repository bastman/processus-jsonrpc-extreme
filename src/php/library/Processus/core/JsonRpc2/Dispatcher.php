<?php

namespace Processus\Lib\JsonRpc2;

    class Dispatcher
    {

        public function run()
        {

            $gateway = new Gateway();
            $gateway->init();
            $gateway->setIsDebugEnabled(true);

            $connection = new Connection();
            $gateway->setConnection($connection);
            $gateway->run();



        }

    }


