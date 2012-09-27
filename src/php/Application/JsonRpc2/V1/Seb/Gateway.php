<?php

namespace Application\JsonRpc2\V1\Seb;


class Gateway
    extends
    \Processus\Lib\JsonRpc2\Gateway
    {

        /**
         * @var array
         */
        protected $_responseHeaders = array(
            "Cache-Control: no-cache",
            "Content-Type: application/json; charset=utf-8",
        );

        /**
         * @var array
         */
        protected $_config = array(
            'enabled' => true,
            'requestBatchMaxItems' => 100,
            'serverClassName' => '{{NAMESPACE}}\\Server',
            'authClassName' => '{{NAMESPACE}}\\Auth',

        );


    }


