<?php

namespace Processus\Lib\JsonRpc2;

    class RpcRequestVo

        extends BaseVo

        implements
        \Processus\Lib\JsonRpc2\Interfaces\RpcRequestVoInterface
    {

        /**
         * @var array
         */
        protected $_data = array(
            'id' => null,
            'version' => '',
            'jsonrpc' => '',
            'method' => '',
            'params' => array(),
        );



        /**
         * @param $id string|int|float|null
         * @return RpcRequestVo
         */
        public function setId($id)
        {
            $this->setDataKey('id', $id);

            return $this;
        }

        /**
         * @return RpcRequestVo
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
         * @return RpcRequestVo
         */
        public function setVersion($version)
        {
            $this->setDataKey('version', $version);

            return $this;
        }

        /**
         * @return RpcRequestVo
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
         * @return RpcRequestVo
         */
        public function setJsonrpc($jsonrpc)
        {
            $this->setDataKey('jsonrpc', $jsonrpc);

            return $this;
        }
        /**
         * @return RpcRequestVo
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
         * @return string
         */
        public function getMethod()
        {
            $result = '';

            $value = $this->getDataKey('method');
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
         * @param $method string
         * @return RpcRequestVo
         */
        public function setMethod($method)
        {
            $this->setDataKey('method', $method);

            return $this;
        }

        /**
         * @return RpcRequestVo
         */
        public function unsetMethod()
        {
            $this->unsetDataKey('method');

            return $this;
        }

        /**
         * @return bool
         */
        public function hasMethod()
        {
            $value = $this->getMethod();

            return (
                (is_string($value))
                    && ($value !== '')
            );
        }

        /**
         * @return array
         */
        public function getParams()
        {
            $value = $this->getDataKey('params');

            if(!is_array($value)) {
                $value = array();
            }

            return $value;
        }

        /**
         * @param array $params
         * @return RpcRequestVo
         */
        public function setParams($params = array())
        {
            $this->setData('params', $params);

            return $this;
        }

        /**
         * @return RpcRequestVo
         */
        public function unsetParams()
        {
            $this->unsetDataKey('params');

            return $this;
        }

        /**
         * @return bool
         */
        public function hasParams()
        {

            $value = $this->getParams();

            return (
                (is_array($value))
                    && ($value !== array())
            );
        }

    }


