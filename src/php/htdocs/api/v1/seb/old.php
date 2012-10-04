<?php

require_once "../../api.bootstrap.php";

$bootstrap = \Application\ApplicationBootstrap::getInstance();
$bootstrap->init();
$gtw = new \Application\JsonRpc2\V1\SebOldServer\Gateway();
$bootstrap->setGateway($gtw);
$gtw->run();