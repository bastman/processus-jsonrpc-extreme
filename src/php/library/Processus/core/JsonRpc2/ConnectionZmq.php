<?php

namespace Processus\Lib\JsonRpc2;

    class ConnectionZmq
        extends Connection
    {


        /**
         * @var \ZMQSocket|null
         */
        protected $_socket;

        /**
         * @var
         */
        protected $_socketReadMode;

        /**
         * @return \ZMQSocket
         */
        public function newSocket($zmqContext, $zmqSocketType)
        {
            $socket = new \ZMQSocket($zmqContext, $zmqSocketType);

            return $socket;
        }

        /**
         * @return bool
         */
        public function  hasSocket()
        {

            return ($this->_socket instanceof \ZMQSocket);
        }


        /**
         * @return ConnectionZmq
         * @throws \Exception
         */
        public function requireZmq()
        {

            $errorMessage = 'ZMQSocket not available. Install it!'
                .' '.__METHOD__
                .' ' .get_class($this)
                ;
            try {
                if(!class_exists('\ZMQSocket')) {

                    throw new \Exception(
                        $errorMessage
                    );
                }

            } catch(\Exception $e) {

                throw new \Exception(
                    $errorMessage
                );

            }

            return $this;
        }

        /**
         * @param $socket
         */
        public function setSocket($socket)
        {
            $this->_socket = $socket;

            return $this;
        }


        /**
         * @return \ZMQSocket|null
         */
        public function getSocket()
        {
            $result = null;

            if(!$this->hasSocket()) {

                return $result;
            }

            return $this->_socket;

        }

        /**
         * @param $mode
         * @return ConnectionZmq
         */
        public function setSocketReadMode($mode)
        {
            $this->_socketReadMode = $mode;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getSocketReadMode()
        {

            return $this->_socketReadMode;
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

            if(!$this->getIsInputBufferEnabled()) {

                $socket = $this->getSocket();
                $requestText = '' . $socket->recv($this->getSocketReadMode());
                $this->setInputBuffer($requestText);

                return $requestText;
            }


            if(!$this->hasInputBuffer()) {

                $socket = $this->getSocket();
                $requestText = '' . $socket->recv($this->getSocketReadMode());
                $this->setInputBuffer($requestText);

                return $requestText;
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
            $header = ''. $header;

            echo ($header);

            return $this;
        }

        /**
         * @param $status string
         * @return Connection
         */
        public function writeStatus($status)
        {
            $headerText = '';

            switch($status) {


                case GatewayStatusType::STATUS_FORBIDDEN:

                    $headerText = 'Forbidden';

                    break;

                case GatewayStatusType::STATUS_BAD_REQUEST:

                    $headerText = 'Bad Request';

                    break;

                case GatewayStatusType::STATUS_INTERNAL_SERVER_ERROR:

                    $headerText = 'Internal Server Error';

                    break;

                default:

                    $headerText = 'Internal Server Error';

                    break;
            }


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
            $message = ''. $message;

            echo ' ERROR: ' . $message;

            return $this;
        }




    }


