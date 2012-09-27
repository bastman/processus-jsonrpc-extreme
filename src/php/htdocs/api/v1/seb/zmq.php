<?php

require_once "../../api.bootstrap.php";

$bootstrap = \Application\ApplicationBootstrap::getInstance();
$bootstrap->init();
$app = new \Application\JsonRpc2\V1\Seb\DispatcherZmq();
$app->run();

//$bootstrap->setGateway($gtw);
//$gtw->setIsDebugEnabled(true);
//$gtw->run();