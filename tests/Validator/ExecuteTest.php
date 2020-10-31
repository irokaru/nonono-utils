<?php

namespace nonono;

use PHPUnit\Framework\TestCase;

class ExecuteTest extends TestCase
{
    public function testErrors()
    {
        $data = [
            'int' => 100,
            'str' => 'hogehoge',
            'arr' => [1, 2, 3],
        ];

        $int_patterns = [
            ['type' => 'int', 'min' => 10, 'max' => 100],
            ['type' => 'int', 'min' => 101],
            ['type' => 'int', 'max' => 99],
            ['type' => 'int', 'min' => 101, 'name' => '数字'],
            ['type' => 'int', 'max' => 99,  'name' => '数字'],
        ];

        $str_patterns = [
            ['type' => 'string', 'min' => 5, 'max' => 100],
            ['type' => 'string', 'min' => 9],
            ['type' => 'string', 'max' => 7],
            ['type' => 'string', 'min' => 9, 'name' => '文字'],
            ['type' => 'string', 'max' => 7, 'name' => '文字'],
        ];

        $arr_patterns = [
            ['type' => 'array', 'min' => 1, 'max' => 5],
            ['type' => 'array', 'min' => 4],
            ['type' => 'array', 'max' => 2],
            ['type' => 'array', 'min' => 4, 'name' => '配列'],
            ['type' => 'array', 'max' => 2, 'name' => '配列'],
        ];

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
                ['int' => $int_patterns[0], 'str' => $str_patterns[0], 'arr' => $arr_patterns[0]],
            ],
            [
                ['int' => ['int'.$int_error[0]], 'str' => ['str'.$str_error[0]], 'arr' => ['arr'.$arr_error[0]]],
                ['int' => $int_patterns[1],      'str' => $str_patterns[1],      'arr' => $arr_patterns[1]], ],
            [
                ['int' => ['int'.$int_error[1]], 'str' => ['str'.$str_error[1]], 'arr' => ['arr'.$arr_error[1]]],
                ['int' => $int_patterns[2],      'str' => $str_patterns[2],      'arr' => $arr_patterns[2]],
            ],
            [
                ['int' => ['数字'.$int_error[0]], 'str' => ['文字'.$str_error[0]], 'arr' => ['配列'.$arr_error[0]]],
                ['int' => $int_patterns[3],       'str' => $str_patterns[3],       'arr' => $arr_patterns[3]],
            ],
            [
                ['int' => ['数字'.$int_error[1]], 'str' => ['文字'.$str_error[1]], 'arr' => ['配列'.$arr_error[1]]],
                ['int' => $int_patterns[4],       'str' => $str_patterns[4],       'arr' => $arr_patterns[4]],
            ],
            [
                ['int' => ['文字'.$type_error], 'str' => ['配列'.$type_error], 'arr' => ['数字'.$type_error]],
                ['int' => $str_patterns[4],     'str' => $arr_patterns[4],     'arr' => $int_patterns[4]],
            ],
        ];

        foreach ($suites as $suite) {
            $v = new Validator($data);

            foreach ($suite[1] as $key => $rule) {
                $v->rules($key, $rule);
            }

            $this->assertEquals($suite[0], $v->errors(), json_encode($suite));
        }
    }
}
