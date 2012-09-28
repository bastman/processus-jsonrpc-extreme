<?php

namespace Processus\Lib\JsonRpc2\ProcsUs;

    class Gateway
        extends
        \Processus\Lib\JsonRpc2\Gateway

    {


        // =========== PROCESSUS: MISC ======================
        /**
         * @override
         * @param \Processus\Lib\JsonRpc2\Interfaces\RpcInterface $rpc
         * @return array
         */
        protected function _getRpcResponseData(
            \Processus\Lib\JsonRpc2\Interfaces\RpcInterface $rpc
        )
        {

            $rpcResponseData = parent::_getRpcResponseData($rpc);

            $isDebugEnabled = $this->getIsDebugEnabled();

            if(!$isDebugEnabled) {

                return $rpcResponseData;
            }

            $rpcResponseData['debug'] = $this->_getResponseDataDebugInfo();

            return $rpcResponseData;
        }


        /**
         * @return array
         */
        protected function _getResponseDataDebugInfo()
        {

            $profiler =
                Util::getProcessusProfiler();
            $system =
                Util::getProcessusSystem();
            $serverParams =
                Util::
                    getProcessusServerParams();
            $bootstrap =
                Util::
                    getProcessusBootstrap();

            $memory = array(
                'usage' => $system->getMemoryUsage(),
                'usage_peak' => $system->getMemoryPeakUsage()
            );

            $app = array(
                'start' => $profiler->applicationProfilerStart(),
                'end' => $profiler->applicationProfilerEnd(),
                'duration' => $profiler->applicationDuration()
            );

            $system = array(
                'request_time' => $serverParams->getRequestTime()
            );

            $requireList = $bootstrap->getFilesRequireList();

            $fileStack = array(
                'list' => $requireList,
                'total' => count($requireList)
            );

            $debugInfo = array(
                'gateway' => get_class($this),
                'server' => get_class($this->getServer()),
                'memory' => $memory,
                'app' => $app,
                'system' => $system,
                'profiling' => $profiler->getProfilerStack(),
                'fileStack' => $fileStack,
            );

            return $debugInfo;

        }






    }


