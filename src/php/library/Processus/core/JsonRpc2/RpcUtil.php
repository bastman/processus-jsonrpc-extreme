<?php

namespace Processus\Lib\JsonRpc2;

class RpcUtil
{

    /**
     * @var array
     */
    private static $_namespaceNameCache = array();

    /**
     * @var array
     */
    private static $_reflectionClassCache = array();


    /**
     * @var array
     */
    private static $_rpcMethodParsedCache = array();


    /**
     * @param string $text
     * @param bool $assoc
     * @param bool $marshallExceptions
     * @return mixed|null
     * @throws \Exception
     */
    public static function jsonDecode(
        $text, $assoc, $marshallExceptions
    )
    {
        $assoc=($assoc===true);
        $marshallExceptions=($marshallExceptions===true);

        $result = null;
        try {
            $result = json_decode($text, $assoc);
        } catch(\Exception $e) {
            $result = null;
            if($marshallExceptions) {

                throw $e;
            }
        }

        return $result;

    }

    /**
     * @param mixed $value
     * @param bool $marshallExceptions
     * @return null|string
     * @throws \Exception
     */
    public static function jsonEncode(
        $value, $marshallExceptions
    )
    {
        $marshallExceptions=($marshallExceptions===true);

        $result = null;
        try {
            $result = json_encode($value);
        } catch(\Exception $e) {
            $result = null;
            if($marshallExceptions) {

                throw $e;
            }
        }

        if(!is_string($result)) {
            $result = null;
        }

        return $result;

    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isAssocArray($value)
    {
        $result = false;

        if(!is_array($value)) {

            return $result;
        }

        $isAssocArray = (array_keys($value)
            !== range(0, count($value) - 1)
        );

        return $isAssocArray;
    }


    /**
     * @param object|string $instance
     * @return string
     */
    public static function getClassnameNice($instance)
    {
        $result = 'null';

        $className = null;
        if(is_string($instance)) {
            $className = $instance;
        }

        if(is_object($instance)) {

            try {
                $className = get_class($instance);
            } catch(\Exception $e) {
                //NOP
            }
        }


        if(!is_string($className)) {

            return $result;
        }

        if(empty($className)) {

            return $result;
        }

        $classNameNice = str_replace(
            array('_', '\\'),
            '.',
            $className
        );

        return $classNameNice;
    }


    /**
     * @param $instance
     * @return string
     */
    public static function getNamespaceName($instance)
    {
        $result = '';

        $className = null;
        if(is_string($instance)) {
            $className = $instance;
        }

        if(is_object($instance)) {
            try {
                $className = get_class($instance);
            } catch(\Exception $e) {
                //NOP
            }
        }

        if(!is_string($className)) {

            return $result;
        }

        if(empty($className)) {

            return $result;
        }

        $namespaceName = null;

        if(array_key_exists($className, self::$_namespaceNameCache)) {
            $namespaceName = self::$_namespaceNameCache[$className];
        }
        if ((is_string($namespaceName)) && (!empty($namespaceName))) {

            return $namespaceName;
        } else {
            $namespaceName = null;
        }


        /**
        try {
            $reflectionClass = new \ReflectionClass($className);
            $namespaceName = $reflectionClass->getNamespaceName();
        } catch(\Exception $e) {
            //NOP
        }
        **/

        $parts = (array)explode('\\', $className);
        array_pop($parts);
        $namespaceName = implode('\\', (array)$parts);

        if(!is_string($namespaceName)) {

            return $result;
        }

        if(empty($namespaceName)) {

            return $result;
        }

        self::$_namespaceNameCache[$className] = $namespaceName;

        return $namespaceName;
    }


    /**
     * @param $class
     * @return null|\ReflectionClass
     */
    public static function getReflectionClass($class)
    {
        $result = null;

        $className = null;
        if(is_object($class)) {
            try {
                $className = get_class($class);
            }catch(\Exception $e) {
                // NOP
            }
        }

        if(!is_string($className)) {

            return $result;
        }

        if(empty($className)) {

            return $result;
        }

        if(array_key_exists($className, self::$_reflectionClassCache)) {
            $reflectionClass = self::$_reflectionClassCache[$className];
            if($reflectionClass instanceof \ReflectionClass) {

                return $reflectionClass;
            }
        }

        $reflectionClass = new \ReflectionClass($className);
        self::$_reflectionClassCache[$className] = $reflectionClass;

        return $reflectionClass;

    }

    /**
     * @param string $rpcMethod
     * @return array
     */
    public static function parseRpcMethod($rpcMethod)
    {
        $rpcMethod = '' . strtolower(trim('' . $rpcMethod));

        if(array_key_exists($rpcMethod, self::$_rpcMethodParsedCache)) {
            $rpcMethodParsed = self::$_rpcMethodParsedCache[$rpcMethod];

            if(is_array($rpcMethodParsed)) {

                return $rpcMethodParsed;
            }
        }

        $parts = (array)explode('.', $rpcMethod);
        $_parts = array();
        foreach ($parts as $part) {
            $part = '' . ucfirst(trim('' . $part));
            $_parts[] = $part;
        }
        $parts = $_parts;

        $rpcMethodName = '' . strtolower('' . array_pop($parts));
        $rpcClassName = '' . array_pop($parts);
        $rpcPackageName = '' . implode('.', $parts);
        $rpcQualifiedClassName = '' . implode(
            '.',
            array(
                $rpcPackageName,
                $rpcClassName,
            )
        );

        $result = array(
            'rpcMethod' => $rpcMethod,
            'rpcPackageName' => $rpcPackageName,
            'rpcClassName' => $rpcClassName,
            'rpcMethodName' => $rpcMethodName,
            'rpcQualifiedClassName' => $rpcQualifiedClassName,
        );

        self::$_rpcMethodParsedCache[$rpcMethod] = $result;

        return $result;

    }
}


