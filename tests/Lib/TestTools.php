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
     * @param \class  $class
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
     * @param \class  $class
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
}
