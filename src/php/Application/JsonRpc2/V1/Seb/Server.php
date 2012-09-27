<?php
namespace Application\JsonRpc2\V1\Seb;


class Server
    extends
    \Processus\Lib\JsonRpc2\Server
{

    /**
     * @var array
     */
    protected $_servicesList = array(

        array(
            "serviceName" => "Seb.Test",
            "className" => "{{NAMESPACE}}\\WebService\\Test",
            "isValidateMethodParamsEnabled" => true,
            "classMethodFilter" => array(
                "allow" => array(
                    "*",
                ),
                "deny" => array(
                    //'*get*',
                    "*myPrivateMethod"
                ),
            ),

        ),


    );

}