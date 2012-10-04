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

    /**
     * @param string|object $class
     * @param bool $marshallExceptions
     * @return \ReflectionClass
     * @throws \Exception
     */
    public static function newReflectionClass($class, $marshallExceptions)
    {
        $result = null;

        $marshallExceptions = ($marshallExceptions===true);

        try {

            if (
                ((is_string($class)) && (!empty($class)))
                || (is_object($class))
            ) {

                $reflectionClass = new \ReflectionClass($class);

                return $reflectionClass;
            }

            if($marshallExceptions) {

                throw new \Exception(
                    'Invalid parameter class at '.__METHOD__
                );
            }

            return $result;

        } catch(\Exception $e) {

            if($marshallExceptions) {

                throw $e;
            }
        }

        return $result;
    }

    /**
     * @param object $namespaceScope
     * @param string $classNameTemplate
     * @param array $methodArgs
     * @param bool $marshallExceptions
     * @return null|object
     * @throws \Exception
     */
    public static function newClassInstanceFromTemplateString(
        $namespaceScope,
        $classNameTemplate,
        $methodArgs = array(),
        $marshallExceptions
    )
    {



        $marshallExceptions = ($marshallExceptions===true);

        if(!is_array($methodArgs)) {
            $methodArgs = array();
        }

        $instance = null;

        try {
            $namespaceName = $namespaceScope;
            if(!is_string($namespaceScope)) {
                $namespaceName = self::getNamespaceName($namespaceScope);
            }

            $className = str_replace(
                array(
                    '{{NAMESPACE}}',
                    '.'
                ),
                array(
                    $namespaceName,
                    '\\'
                ),
                $classNameTemplate
            );

            if(empty($className)) {

                if($marshallExceptions) {

                    throw new \Exception('Invalid className');
                }

                return $instance;
            }

            if(count($methodArgs)<1) {

                if(class_exists($className)) {

                    $instance = new $className();

                }

                return $instance;
            }


            $reflectionClass = self::newReflectionClass($className, false);

            if($reflectionClass instanceof \ReflectionClass) {

                $instance = $reflectionClass->newInstanceArgs($methodArgs);

                return $instance;

            }

            if($marshallExceptions) {

                throw new \Exception('Class not found');
            }


        } catch(\Exception $e) {

            if($marshallExceptions) {

                throw $e;
            }

        }

        return $instance;

    }



    /**
     * @param array $data
     * @param array $signKeys
     * @param string $appSecret
     * @param string $signedRequestAlgorithm
     * @param int|string $issuedAt
     * @return string
     */
    public static function createRequestSignature(

        $data = array(),
        $signKeys = array(),
        $appSecret = '',
        $signedRequestAlgorithm='HMAC-SHA256',
        $issuedAt = 0
    )
    {

        if(!is_int($issuedAt)) {
            $issuedAt = 0;
        }

        if (!is_array($data)) {
            $data = array();
        }
        if(!is_array($signKeys)) {
            $signKeys = array();
        }

        $signedRequestAlgorithm = strtoupper($signedRequestAlgorithm);

        $_data = array();
        foreach($signKeys as $key) {
            $value = null;
            if(array_key_exists($key, $data)) {
                $value = $data[$key];
            }
            $_data[$key] = $value;
        }
        $data = $_data;

        // sort keys
        uksort($data, 'strcmp');

        $json = (string)self::jsonEncode($data, false);

        $signatureParts =
            array(
                (string)strtoupper($signedRequestAlgorithm),
                (string)$issuedAt,
                (string)self::base64UrlEncodeUrlSafe($json),
            );

        $b64Data = self::base64UrlEncodeUrlSafe(implode(
            '.', $signatureParts
        ));

        $rawSig = hash_hmac('sha256', $b64Data, $appSecret, $raw = true);

        $sig = (string)implode(
            '.',
            array(
                $signatureParts[0],
                $signatureParts[1],
                $rawSig,
            )
        );

        $sig = (string)self::base64UrlEncodeUrlSafe($sig);

        return $sig;

    }

    /**
     * @param string $signature
     * @param array $data
     * @param array $signKeys
     * @param string $appSecret
     * @param string $signedRequestAlgorithm
     * @return bool
     */
    public static function validateSignedRequest(
        $signature = '',
        $data = array(),
        $signKeys = array(),
        $appSecret = '',
        $signedRequestAlgorithm='HMAC-SHA256'
    ) {

        $result = false;

        if(!is_string($signature)) {

            return $result;
        }

        $sigGiven = $signature;
        $sigDecoded = self::base64UrlDecodeUrlSafe($sigGiven);

        list(
            $algorithmGiven,
            $issuedAtGiven,
            $rawSigGiven
            ) = explode('.', $sigDecoded, 3)
        ;

        if(!is_string($algorithmGiven)) {

            return $result;
        }

        if(!is_string($issuedAtGiven)) {

            return $result;
        }

        if(!is_string($rawSigGiven)) {

            return $result;
        }

        $issuedAtGiven = (int)$issuedAtGiven;
        $signedRequestAlgorithm = strtoupper($signedRequestAlgorithm);
        $algorithmGiven = strtoupper($signedRequestAlgorithm);

        if($algorithmGiven !== $signedRequestAlgorithm) {

            return $result;
        }

        if(!is_array($data)) {
            $data = array();
        }
        if(!is_array($signKeys)) {
            $signKeys = array();
        }

        $signatureExpected = self::createRequestSignature(
            $data,
            $signKeys,
            $appSecret,
            $signedRequestAlgorithm,
            $issuedAtGiven
        );

        $result = ($signatureExpected === $sigGiven);

        return $result;
    }




    /**
     * @see: facebook-php-sdk
     * Base64 encoding that doesn't need to be urlencode()ed.
     * Exactly the same as base64_encode except it uses
     *   - instead of +
     *   _ instead of /
     *   No padded =
     *
     * @param string $input base64UrlEncoded string
     * @return string
     */
    protected static function base64UrlDecodeUrlSafe($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * @see: facebook-php-sdk
     * Base64 encoding that doesn't need to be urlencode()ed.
     * Exactly the same as base64_encode except it uses
     *   - instead of +
     *   _ instead of /
     *
     * @param string $input string
     * @return string base64Url encoded string
     */
    protected static function base64UrlEncodeUrlSafe($input) {
        $str = strtr(base64_encode($input), '+/', '-_');
        $str = str_replace('=', '', $str);
        return $str;
    }



}


