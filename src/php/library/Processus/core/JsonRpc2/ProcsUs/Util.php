<?php

namespace Processus\Lib\JsonRpc2\ProcsUs;

    class Util
    {

        /**
         * @static
         * @return \Processus\Lib\Profiler\ProcessusProfiler
         */
        public static function getProcessusProfiler()
        {
            return \Processus\Lib\Profiler\ProcessusProfiler::getInstance();
        }


        /**
         * @static
         * @return \Processus\Lib\System\System
         */
        public static function getProcessusSystem()
        {
            return \Processus\Lib\System\System::getInstance();
        }


        /**
         * @static
         * @return \Processus\Lib\Server\ServerInfo
         */
        public static function getProcessusServerParams()
        {
            return \Processus\Lib\Server\ServerInfo::getInstance();
        }

        /**
         * @static
         * @return \Processus\ProcessusBootstrap
         */
        public static function getProcessusBootstrap()
        {
            return \Processus\ProcessusContext::getInstance()->getBootstrap();
        }

        /**
         * @return \Application\Core\Lib\Auth\FacebookAuth
         */
        public static function newProcessusFacebookAuth()
        {

            return new \Application\Core\Lib\Auth\FacebookAuth();
        }




}

