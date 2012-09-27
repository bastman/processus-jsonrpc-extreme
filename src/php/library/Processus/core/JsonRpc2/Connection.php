<?php

namespace Processus\Lib\JsonRpc2;

    class Connection
        implements
        \Processus\Lib\JsonRpc2\Interfaces\ConnectionInterface
    {


        /**
         * @var bool
         */
        protected $_isInputBufferEnabled = false;

        /**
         * @var null|string
         */
        protected $_inputBuffer = null;


        // ============= .gateway ========================

        /**
         * @var \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface
         */
        protected $_gateway;

        /**
         * @param \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface $gateway
         * @return Connection
         */
        public function setGateway(
            \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface $gateway
        )
        {
            $this->_gateway = $gateway;

            return $this;
        }

        /**
         * @return Connection
         */
        public function unsetGateway()
        {
            $this->_gateway = null;

            return $this;
        }
        /**
         * @return \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface|null
         */
        public function getGateway()
        {
            return $this->_gateway;
        }

        /**
         * @return bool
         */
        public function hasGateway()
        {
            return (
                $this->_gateway
                    instanceof
                    \Processus\Lib\JsonRpc2\Interfaces\GatewayInterface
            );
        }


        // ===================================================

        /**
         * @param $enabled
         * @return Connection
         */
        public function setIsInputBufferEnabled($enabled)
        {

            $this->_isInputBufferEnabled = ($enabled===true);

            return $this;
        }

        /**
         * @return bool
         */
        public function getIsInputBufferEnabled()
        {

            return ($this->_isInputBufferEnabled===true);
        }

        /**
         * @param $text null|string
         * @throws \Exception
         */
        public function setInputBuffer($text)
        {
            if($text === null) {
                $text = '';
            }

            if(!is_string($text)) {
                throw new \Exception(
                    'Invalid parameter at '.__METHOD__.get_class($this)
                );
            }

            $this->_inputBuffer = $text;
        }

        /**
         * @return Connection
         */
        public function unsetInputBuffer()
        {
            $this->_inputBuffer = null;

            return $this;
        }

        /**
         * @return null|string
         */
        public function getInputBuffer()
        {
            $result = null;

            $value = $this->_inputBuffer;

            if(!is_string($value)) {

                return $result;
            }

            return $value;
        }

        /**
         * @return bool
         */
        public function hasInputBuffer()
        {
            $value = $this->getInputBuffer();

            return (is_string($value));
        }


        /**
         * @param $text string
         * @return Connection
         */
        public function write($text)
        {

            echo '' . $text;

            return $this;
        }

        /**
         * @return string
         */
        public function read()
        {

            $requestText = '';
            if(!$this->hasInputBuffer()) {

                $requestText = '';
                $this->setInputBuffer($requestText);
            }

            $requestText = '' . $this->getInputBuffer();


            return $requestText;
        }


        /**
         * @param $header string
         * @return Connection
         */
        public function writeHeader($header)
        {
            $header = 'HEADER: '. $header;

            header($header);

            return $this;
        }

        /**
         * @param $status string
         * @return Connection
         */
        public function writeStatus($status)
        {
            $headerText = 'STATUS: ' . $status;

            echo($headerText);

            return $this;
        }
        /**
         * @param $message string
         * @return Connection
         */
        public function writeErrorLog($message)
        {
            $message = ''. $message;

            echo('ERRORLOG: '. $message);

            return $this;
        }
        /**
         * @param $message string
         * @return Connection
         */
        public function writeErrorMessage($message)
        {
            $message = 'MESSAGE: '. $message;

            echo $message;

            return $this;
        }


    }


