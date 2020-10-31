<?php

namespace nonono\Validator;

use PHPUnit\Framework\TestCase;

class ExecuteTest extends TestCase
{
    protected static $data = [
        'int' => 100,
        'str' => 'hogehoge',
        'arr' => [1, 2, 3],
    ];

    protected static $int_patterns = [
        ['type' => 'int', 'min' => 10, 'max' => 100],
        ['type' => 'int', 'min' => 101],
        ['type' => 'int', 'max' => 99],
        ['type' => 'int', 'min' => 101, 'name' => '数字'],
        ['type' => 'int', 'max' => 99,  'name' => '数字'],
    ];

    protected static $str_patterns = [
        ['type' => 'string', 'min' => 5, 'max' => 100],
        ['type' => 'string', 'min' => 9],
        ['type' => 'string', 'max' => 7],
        ['type' => 'string', 'min' => 9, 'name' => '文字'],
        ['type' => 'string', 'max' => 7, 'name' => '文字'],
    ];

    protected static $arr_patterns = [
        ['type' => 'array', 'min' => 1, 'max' => 5],
        ['type' => 'array', 'min' => 4],
        ['type' => 'array', 'max' => 2],
        ['type' => 'array', 'min' => 4, 'name' => '配列'],
        ['type' => 'array', 'max' => 2, 'name' => '配列'],
    ];

    public function testErrors()
    {
        $int_error = [
            'は101以上にしてください',
            'は99以下にしてください',
        ];

        $str_error = [
            'は9文字以上にしてください',
            'は7文字以下にしてください',
        ];

        $arr_error = [
            'は4要素以上にしてください',
            'は2要素以下にしてください',
        ];

        $type_error = 'の型が不正です';

        $suites = [
            //expect, rules
            [
                [],
                ['int' => static::$int_patterns[0], 'str' => static::$str_patterns[0], 'arr' => static::$arr_patterns[0]],
            ],
            [
                ['int' => ['int'.$int_error[0]], 'str' => ['str'.$str_error[0]], 'arr' => ['arr'.$arr_error[0]]],
                ['int' => static::$int_patterns[1],      'str' => static::$str_patterns[1],      'arr' => static::$arr_patterns[1]], ],
            [
                ['int' => ['int'.$int_error[1]], 'str' => ['str'.$str_error[1]], 'arr' => ['arr'.$arr_error[1]]],
                ['int' => static::$int_patterns[2],      'str' => static::$str_patterns[2],      'arr' => static::$arr_patterns[2]],
            ],
            [
                ['int' => ['数字'.$int_error[0]], 'str' => ['文字'.$str_error[0]], 'arr' => ['配列'.$arr_error[0]]],
                ['int' => static::$int_patterns[3],       'str' => static::$str_patterns[3],       'arr' => static::$arr_patterns[3]],
            ],
            [
                ['int' => ['数字'.$int_error[1]], 'str' => ['文字'.$str_error[1]], 'arr' => ['配列'.$arr_error[1]]],
                ['int' => static::$int_patterns[4],       'str' => static::$str_patterns[4],       'arr' => static::$arr_patterns[4]],
            ],
            [
                ['int' => ['文字'.$type_error], 'str' => ['配列'.$type_error], 'arr' => ['数字'.$type_error]],
                ['int' => static::$str_patterns[4],     'str' => static::$arr_patterns[4],     'arr' => static::$int_patterns[4]],
            ],
        ];

        foreach ($suites as $suite) {
            $v = new Validator(static::$data);

            foreach ($suite[1] as $key => $rule) {
                $v->rules($key, $rule);
            }

            $this->assertEquals($suite[0], $v->errors(), json_encode($suite));
        }
    }

    public function testExec()
    {
        $suites = [
            //expect, rules
            [
                true,
                ['int' => static::$int_patterns[0], 'str' => static::$str_patterns[0], 'arr' => static::$arr_patterns[0]],
            ],
            [
                false,
                ['int' => static::$int_patterns[1],      'str' => static::$str_patterns[1],      'arr' => static::$arr_patterns[1]], ],
            [
                false,
                ['int' => static::$int_patterns[2],      'str' => static::$str_patterns[2],      'arr' => static::$arr_patterns[2]],
            ],
            [
                false,
                ['int' => static::$int_patterns[3],       'str' => static::$str_patterns[3],       'arr' => static::$arr_patterns[3]],
            ],
            [
                false,
                ['int' => static::$int_patterns[4],       'str' => static::$str_patterns[4],       'arr' => static::$arr_patterns[4]],
            ],
            [
                false,
                ['int' => static::$str_patterns[4],     'str' => static::$arr_patterns[4],     'arr' => static::$int_patterns[4]],
            ],
        ];

        foreach ($suites as $suite) {
            $v = new Validator(static::$data);

            foreach ($suite[1] as $key => $rule) {
                $v->rules($key, $rule);
            }

            $this->assertEquals($suite[0], $v->exec(), json_encode($suite));
        }
    }
}
