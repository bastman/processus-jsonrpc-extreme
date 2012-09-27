<?php

namespace Processus\Lib\JsonRpc2;

    class ConnectionHttp
        extends Connection
    {



        /**
         * @var null|string
         */
        protected $_inputBuffer = null;




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

                $requestText = '' . file_get_contents('php://input');
                $this->setInputBuffer($requestText);

                return $requestText;
            }


            if(!$this->hasInputBuffer()) {

                $requestText = '' . file_get_contents('php://input');
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

            header($header);

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

                    $headerText = 'HTTP/1.0 403 Forbidden';

                    break;

                case GatewayStatusType::STATUS_BAD_REQUEST:

                    $headerText = 'HTTP/1.0 400 Bad Request';

                    break;

                case GatewayStatusType::STATUS_INTERNAL_SERVER_ERROR:

                    $headerText = 'HTTP/1.0 500 Internal Server Error';

                    break;

                default:

                    $headerText = 'HTTP/1.0 500 Internal Server Error';

                    break;
            }


            header($headerText);

            return $this;
        }
        /**
         * @param $message string
         * @return Connection
         */
        public function writeErrorLog($message)
        {
            $message = ''. $message;

            header('x-processus-gateway-error: '. $message);

            return $this;
        }
        /**
         * @param $message string
         * @return Connection
         */
        public function writeErrorMessage($message)
        {
            $message = ''. $message;

            echo $message;

            return $this;
        }




    }


