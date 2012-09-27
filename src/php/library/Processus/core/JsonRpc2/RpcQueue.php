<?php

namespace Processus\Lib\JsonRpc2;

    class RpcQueue implements
        \Processus\Lib\JsonRpc2\Interfaces\RpcQueueInterface
    {

        /**
         * @var bool
         */
        protected $_isBatched = false;


        /**
         * @var array
         */
        protected $_items = array();

        /**
         * @var RPC|null
         */
        protected $_currentItem;


        /**
         * @param \Processus\Lib\JsonRpc2\Interfaces\RpcInterface $rpc
         * @return RpcQueue
         */
        public function addItem(
            \Processus\Lib\JsonRpc2\Interfaces\RpcInterface $rpc
        )
        {
            $this->_items[] = $rpc;

            return $this;
        }

        /**
         * @return array
         */
        public function getItems()
        {
            return $this->_items;
        }

        /**
         * @return RpcQueue
         */
        public function unsetItems()
        {
            $this->_items = array();

            return $this;
        }

        /**
         * @param $isBatched
         * @return RpcQueue
         */
        public function setIsBatched($isBatched)
        {
            $this->_isBatched = ($isBatched===true);

            return $this;
        }

        /**
         * @return bool
         */
        public function getIsBatched()
        {
            return ($this->_isBatched===true);
        }


        /**
         * @param \Processus\Lib\JsonRpc2\Interfaces\RpcInterface $rpc
         * @return RpcQueue
         */
        public function setCurrentItem(
            \Processus\Lib\JsonRpc2\Interfaces\RpcInterface $rpc
        )
        {
            $this->_currentItem = $rpc;

            return $this;
        }

        public function unsetCurrentItem()
        {
            $this->_currentItem = null;

            return $this;
        }

        /**
         * @return \Processus\Lib\JsonRpc2\Interfaces\RpcInterface|null
         */
        public function getCurrentItem()
        {

            return $this->_currentItem;
        }

        /**
         * @return bool
         */
        public function hasCurrentItem()
        {

            return ($this->_currentItem instanceof Rpc);
        }

    }


