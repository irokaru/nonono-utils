<?php

namespace nonono;

use nonono\Lib\TestTools;

use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{
    public function testRule()
    {
        $v = new Validator([]);

        $key1   = 'hogehoge';
        $rules1 = ['type' => 'int', 'min' => 10, 'max' => 10];

        $v->rules($key1, $rules1);
        $this->assertEquals([$key1 => $rules1], TestTools::getProtectedProperty(Validator::class, '_rules')->getValue($v));

        $key2   = 'fugafuga';
        $rules2 = ['type' => 'string', 'min' => 10, 'max' => 10];

        $v->rules($key2, $rules2);
        $this->assertEquals([$key1 => $rules1, $key2 => $rules2], TestTools::getProtectedProperty(Validator::class, '_rules')->getValue($v));
    }

    public function testRuleExceptionKey()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('key length is greater than 1');

        $v = new Validator([]);

        $key   = '';
        $rules = ['type' => 'int', 'min' => 10, 'max' => 10];

        $v->rules($key, $rules);
    }

    public function testRuleExceptionRules()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid rule');

        $v = new Validator([]);

        $key   = 'test';
        $rules = ['invalid' => 'hogehoge'];

        $v->rules($key, $rules);
    }

    public function testGetRules()
    {
        $v = new Validator([]);

        $expect = ['type', 'min', 'max'];
        $this->assertEquals($expect, TestTools::getProtectedMethod(Validator::class, '_getRules')->invoke($v));
    }

    public function testGetTypes()
    {
        $v = new Validator([]);

        $expect = ['int', 'integer', 'numelic', 'string', 'array', 'assoc_array'];
        $this->assertEquals($expect, TestTools::getProtectedMethod(Validator::class, '_getTypes')->invoke($v));
    }

    public function testCheckRules()
    {
        $v = new Validator([]);

        $suites = [
            //expect, rules
            [true,  ['type'     => 'a']],
            [true,  ['min'      => 'a']],
            [true,  ['max'      => 'a']],
            [false, ['hogehoge' => 'a']],
        ];

        foreach ($suites as $suite) {
            $this->assertEquals($suite[0], TestTools::getProtectedMethod(Validator::class, '_checkRules')->invoke($v, $suite[1]), json_encode($suite));
        }
    }
}
