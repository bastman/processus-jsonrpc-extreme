<?php
namespace Application\JsonRpc2\V1\Seb;


    class DispatcherHttp
        extends
        \Processus\Lib\JsonRpc2\Dispatcher
    {


        public function profile()
        {

            $requestData = array(

                'method' => 'Seb.Test.ping',
            );
            $requestText = json_encode($requestData);


var_dump(__METHOD__);
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

                /*
                $gateway->setRequestData(array(

                        'method' => 'Seb.Test.ping',
                    ));
                */
                //$gateway->setCryptModule()
                //$gateway->setAuthModule(new )

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



        }

        public function run()
        {

            $gateway = new Gateway();
            $gateway->init();
            $gateway->setIsDebugEnabled(true);

            $connection = new ConnectionHttp();
            $gateway->setConnection($connection);
            //$gateway->setCryptModule()
            //$gateway->setAuthModule(new )

            $gateway->run();



        }

    }


