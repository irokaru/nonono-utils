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

    public function testGetNameAsKey()
    {
        $suites = [
            //expect, key, rule
            ['hoge',    'hoge', ['hoge' => ['type' => 'string']]],
            ['keyname', 'hoge', ['hoge' => ['type' => 'string', 'name' => 'keyname']]],
        ];

        foreach ($suites as $suite) {
            $v = new Validator([]);
            TestTools::getProtectedProperty(Validator::class, '_rules')->setValue($v, $suite[2]);
            $this->assertEquals($suite[0], TestTools::getProtectedMethod(Validator::class, '_getNameAsKey')->invoke($v, $suite[1]), json_encode($suite));
        }
    }

    public function testGetDataValueAsKey()
    {
        $suites = [
            //expect, key, data
            ['hoge', 'aaa', ['aaa' => 'hoge']],
            [2,      'bbb', ['bbb' => 2]],
            [[],     'ccc', ['ccc' => []]],
        ];

        foreach ($suites as $suite) {
            $v = new Validator($suite[2]);
            $this->assertEquals($suite[0], TestTools::getProtectedMethod(Validator::class, '_getDataValueAsKey')->invoke($v, $suite[1]), json_encode($suite));
        }
    }

    public function testGetRules()
    {
        $v = new Validator([]);

        $expect = ['type', 'min', 'max'];
        $this->assertEquals($expect, TestTools::getProtectedMethod(Validator::class, '_getRules')->invoke($v));
    }

    public function testFilterRules()
    {
        $v = new Validator([]);

        $suites = [
            //expect, rule
            [[],                ['hogehoge' => 'will delete']],
            [['type' => 'aaa'], ['type' => 'aaa', 'invalid' => 'kieru']],
            [['min'  => 'aaa'], ['min'  => 'aaa', 'invalid' => 'kieru']],
            [['max'  => 'aaa'], ['max'  => 'aaa', 'invalid' => 'kieru']],
            [['name' => 'aaa'], ['name' => 'aaa', 'invalid' => 'kieru']],
        ];

        foreach ($suites as $suite) {
            $this->assertEquals($suite[0], TestTools::getProtectedMethod(Validator::class, '_filterRules')->invoke($v, $suite[1]), json_encode($suite));
        }
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
            [true,  ['type' => 'string']],
            [true,  ['type' => 'int',    'min'      => 1]],
            [true,  ['type' => 'int',    'max'      => 1]],
            [false, ['type' => 'ugera']],
            [false, ['type' => 'int',    'min'      => 'hoge']],
            [false, ['type' => 'int',    'max'      => 'hoge']],
        ];

        foreach ($suites as $suite) {
            $this->assertEquals($suite[0], TestTools::getProtectedMethod(Validator::class, '_checkRules')->invoke($v, $suite[1]), json_encode($suite));
        }
    }

    public function testSetError()
    {
        $v = new Validator([]);

        $suites = [
            //expect, error, key, message
            [['hoge' => ['aaaa']],                    [],                    'hoge', 'aaaa'],
            [['fuga' => ['exist', 'add']],            ['fuga' => ['exist']], 'fuga', 'add'],
            [['hoge' => ['aaaa'], 'fuga' => ['add']], ['hoge' => ['aaaa']],  'fuga', 'add'],
        ];

        foreach ($suites as $suite) {
            $this->assertEquals($suite[0],
                                TestTools::getProtectedMethod(Validator::class, '_setError')->invoke($v, $suite[1], $suite[2], $suite[3]),
                                json_encode($suite));
        }
    }

    public function testGetMessage()
    {
        $v = new Validator([]);

        $suites = [
            //expect, rule, type, name, num
            ['hogeの型が不正です', 'type', 'int',         'hoge', 0],
            ['hogeの型が不正です', 'type', 'integer',     'hoge', 0],
            ['hogeの型が不正です', 'type', 'numelic',     'hoge', 0],
            ['hogeの型が不正です', 'type', 'string',      'hoge', 0],
            ['hogeの型が不正です', 'type', 'array',       'hoge', 0],
            ['hogeの型が不正です', 'type', 'assoc_array', 'hoge', 0],

            ['hogeは0以上にしてください',    'min', 'int',         'hoge', 0],
            ['hogeは1以上にしてください',    'min', 'integer',     'hoge', 1],
            ['hogeは2以上にしてください',    'min', 'numelic',     'hoge', 2],
            ['hogeは3文字以上にしてください','min', 'string',      'hoge', 3],
            ['hogeは4要素以上にしてください','min', 'array',       'hoge', 4],
            ['hogeは5要素以上にしてください','min', 'assoc_array', 'hoge', 5],

            ['hogeは0以下にしてください',    'max', 'int',         'hoge', 0],
            ['hogeは1以下にしてください',    'max', 'integer',     'hoge', 1],
            ['hogeは2以下にしてください',    'max', 'numelic',     'hoge', 2],
            ['hogeは3文字以下にしてください','max', 'string',      'hoge', 3],
            ['hogeは4要素以下にしてください','max', 'array',       'hoge', 4],
            ['hogeは5要素以下にしてください','max', 'assoc_array', 'hoge', 5],
        ];

        foreach ($suites as $suite) {
            $this->assertEquals($suite[0], TestTools::getProtectedMethod(Validator::class, '_getMessage')->invoke($v, $suite[1], $suite[2], $suite[3], $suite[4]), json_encode($suite));
        }
    }
}
