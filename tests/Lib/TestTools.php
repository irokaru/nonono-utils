<?php

namespace nonono\Lib;

class TestTools
{
    /**
     * 期待値を一律で配列に付与する
     * @param mixed $expect
     * @param array $array
     * @return array
     */
    public static function attachExpect($expect, $array): array
    {
        $ret = [];
        foreach ($array as $val) {
            $ret[] = [$expect, $val];
        }

        return $ret;
    }

    /**
     * 非公開メソッドを引っ張ってくる
     * `$method->invoke(class, $args);`
     *
     * @param \class $class
     * @param string $method_name
     * @return \ReflectionMethod
     */
    public static function getProtectedMethod($class, $method_name): \ReflectionMethod
    {
        $ref    = new \ReflectionClass($class);
        $method = $ref->getMethod($method_name);

        $method->setAccessible(true);

        return $method;
    }

    /**
     * 非公開プロパティを引っ張ってくる
     *
     * @param \class $class
     * @param string $property_name
     * @return \ReflectionProperty
     */
    public static function getProtectedProperty($class, $property_name): \ReflectionProperty
    {
        $ref      = new \ReflectionClass($class);
        $property = $ref->getProperty($property_name);

        $property->setAccessible(true);

        return $property;
    }

    /**
     * @param string $url
     * @param string $method
     */
    public static function access(string $url, string $method)
    {
        $parsed_url = parse_url($url);

        $_gets = $parsed_url['query'] ?? '';
        foreach (explode('&', $_gets) as $gets) {
            $parged_get = explode('=', $gets);

            if (count($parged_get) === 0) {
                continue;
            }

            $key        = $parged_get[0];
            $value      = $parged_get[1] ?? '';
            $_GET[$key] = $value;
        }

        $_SERVER['SERVER_NAME'] = $parsed_url['host'] ?? 'example.com';
        $_SERVER['SERVER_PORT'] = isset($parsed_url['port']) ? $parsed_url['path'] : 80;
        $_SERVER['SCRIPT_NAME'] = $parsed_url['path'] ?? '';
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME']
                                . isset($parsed_url['args']) ? ('?' . $parsed_url['args']) : '';

        $_SERVER['REQUEST_METHOD'] = $method;
    }
}
