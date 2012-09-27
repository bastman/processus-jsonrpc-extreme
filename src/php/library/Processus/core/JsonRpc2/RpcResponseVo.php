<?php

namespace Processus\Lib\JsonRpc2;

    class RpcResponseVo

        extends BaseVo

        implements
        \Processus\Lib\JsonRpc2\Interfaces\RpcResponseVoInterface
    {

        /**
         * @var array
         */
        protected $_data = array(
            'id' => null,
            'version' => null,
            'jsonrpc' => null,
            'result' => null,
            'error' => null,
        );

        /**
         * @var \Exception|null
         */
        protected $_exception;


        /**
         * @param $id string|int|float|null
         * @return RpcResponseVo
         */
        public function setId($id)
        {
            $this->setDataKey('id', $id);

            return $this;
        }

        /**
         * @return RpcResponseVo
         */
        public function unsetId()
        {
            $this->unsetDataKey('id');

            return $this;
        }
        /**
         * @return string|int|float|null
         */
        public function getId()
        {
            $result = null;

            $value = $this->getDataKey('id');

            if ($value === null) {

                return $value;
            }
            if (is_string($value)) {

                return $value;
            }
            if (is_int($value)) {

                return $value;
            }
            if (is_float($value)) {

                return $value;
            }

            return $result;
        }

        /**
         * @return bool
         */
        public function hasId()
        {
            $result = true;

            $value = $this->getId();

            if ($value === null) {

                return $result;
            }
            if (is_string($value)) {

                return $result;
            }
            if (is_int($value)) {

                return $result;
            }
            if (is_float($value)) {

                return $result;
            }

            return false;
        }

        /**
         * @param $version string
         * @return RpcResponseVo
         */
        public function setVersion($version)
        {
            $this->setDataKey('version', $version);

            return $this;
        }

        /**
         * @return RpcResponseVo
         */
        public function unsetVersion()
        {
            $this->unsetDataKey('version');

            return $this;
        }
        /**
         * used by jsonrpc v1
         * @return string
         */
        public function getVersion()
        {
            $result = '';

            $value = $this->getDataKey('version');
            try {
                $value = (string) $value;
            }catch(\Exception $e){
                // NOP
            }

            if(!is_string($value)) {
                $value = $result;
            }

            return $value;
        }

        /**
         * @return bool
         */
        public function hasVersion()
        {
            $result = false;

            $value = $this->getVersion();

            if(!is_string($value)) {

                return $result;
            }

            return ($value !== '');
        }

        /**
         * @param $jsonrpc string
         * @return RpcResponseVo
         */
        public function setJsonrpc($jsonrpc)
        {
            $this->setDataKey('jsonrpc', $jsonrpc);

            return $this;
        }
        /**
         * @return RpcResponseVo
         */
        public function unsetJsonrpc()
        {
            $this->unsetDataKey('jsonrpc');

            return $this;
        }

        /**
         * used by jsonrpc v2
         * @return string
         */
        public function getJsonrpc()
        {
            $result = '';

            $value = $this->getDataKey('jsonrpc');
            try {
                $value = (string) $value;
            }catch(\Exception $e){
                // NOP
            }

            if(!is_string($value)) {
                $value = $result;
            }

            return $value;
        }

        /**
         * @return bool
         */
        public function hasJsonrpc()
        {
            $result = false;

            $value = $this->getJsonrpc();

            if(!is_string($value)) {

                return $result;
            }

            return ($value !== '');
        }




        /**
         * @param $result mixed
         * @return RpcResponseVo
         */
        public function setResult($result)
        {
            $this->setDataKey('result', $result);

            return $this;
        }

        /**
         * @return mixed
         */
        public function getResult()
        {
            return $this->getDataKey('result');
        }

        /**
         * @return RpcResponseVo
         */
        public function unsetResult()
        {
            $this->unsetDataKey('result');

            return $this;
        }


        /**
         * @param $error array
         * @return RpcResponseVo
         */
        public function setError($error)
        {

            $this->setDataKey('error', $error);

            return $this;
        }

        /**
         * @return RpcResponseVo
         */
        public function unsetError()
        {
            $this->unsetDataKey('error');

            return $this;
        }
        /**
         * @return array|null
         */
        public function getError()
        {
            $result = null;

            $value = $this->getDataKey('error');
            if (!is_array($value)) {

                return $result;
            }

            return $value;
        }

        /**
         * @return bool
         */
        public function hasError()
        {
            $error = $this->getError();

            return is_array($error);
        }



        /**
         * @param \Exception $exception
         * @return RpcResponseVo
         */
        public function setException(\Exception $exception)
        {
            $this->_exception = $exception;

            return $this;
        }

        /**
         * @return RpcResponseVo
         */
        public function unsetException()
        {
            $this->_exception = null;

            return $this;
        }

        /**
         * @return \Exception|null
         */
        public function getException()
        {
            return $this->_exception;
        }

        /**
         * @return bool
         */
        public function hasException()
        {
            return ($this->_exception instanceof \Exception);
        }



}

