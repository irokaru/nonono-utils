<?php

namespace nonono;

use nonono\Lib\TestParams;
use nonono\Lib\TestTools;

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testIsNumber()
    {
        $suites_ok = TestTools::attachExpect(true, array_merge(
            TestParams::INT, TestParams::INT_COMMA, TestParams::FLOAT,
        ));

        $suites_ng = TestTools::attachExpect(false, array_merge(
            TestParams::STRING_INT, TestParams::STRING_INT_COMMA, TestParams::STRING_FLOAT, TestParams::STRING_WORD,
            TestParams::ARRAY, TestParams::ARRAY_EMPTY, TestParams::OBJECT,
            TestParams::BOOLEAN, TestParams::NULL,
        ));

        foreach (array_merge($suites_ok, $suites_ng) as $suite) {
            $this->assertEquals($suite[0], Validator::isNumber($suite[1]), json_encode($suite));
        }
    }

    public function testIsIntger()
    {
        $suites_ok = TestTools::attachExpect(true, array_merge(
            TestParams::INT,
        ));

        $suites_ng = TestTools::attachExpect(false, array_merge(
            TestParams::INT_COMMA, TestParams::FLOAT,
            TestParams::STRING_INT, TestParams::STRING_INT_COMMA, TestParams::STRING_FLOAT, TestParams::STRING_WORD,
            TestParams::ARRAY, TestParams::ARRAY_EMPTY, TestParams::OBJECT,
            TestParams::BOOLEAN, TestParams::NULL,
        ));

        foreach (array_merge($suites_ok, $suites_ng) as $suite) {
            $this->assertEquals($suite[0], Validator::isInteger($suite[1]), json_encode($suite));
        }
    }

    public function testIsString()
    {
        $suites_ok = TestTools::attachExpect(true, array_merge(
            TestParams::STRING_INT, TestParams::STRING_INT_COMMA, TestParams::STRING_FLOAT, TestParams::STRING_WORD,
        ));

        $suites_ng = TestTools::attachExpect(false, array_merge(
            TestParams::INT, TestParams::INT_COMMA, TestParams::FLOAT,
            TestParams::ARRAY, TestParams::ARRAY_EMPTY, TestParams::OBJECT,
            TestParams::BOOLEAN, TestParams::NULL,
        ));

        foreach (array_merge($suites_ok, $suites_ng) as $suite) {
            $this->assertEquals($suite[0], Validator::isString($suite[1]), json_encode($suite));
        }
    }
}
