<?php

namespace nonono;

use PHPUnit\Framework\TestCase;

class ExecuteTest extends TestCase
{
    public function testErrors()
    {
        $data = ['hoge' => 0];

        $v = new Validator($data);
        $v->rules('hoge', ['type' => 'numelic', 'max' => 1]);

        $this->assertEquals([], $v->errors());
    }
}
