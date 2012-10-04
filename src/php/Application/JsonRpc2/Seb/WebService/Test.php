<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 4/24/12
 * Time: 1:04 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Application\JsonRpc2\V1\Seb\WebService;

class Test extends \Processus\Lib\JsonRpc2\Service
{

    public function ping()
    {

        return 'pong123';
    }

}
