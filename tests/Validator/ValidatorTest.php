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
            TestParams::INT,
            TestParams::INT_COMMA,
            TestParams::FLOAT,
        ));

        $suites_ng = TestTools::attachExpect(false, array_merge(
            TestParams::STRING_INT,
            TestParams::STRING_INT_COMMA,
            TestParams::STRING_FLOAT,
            TestParams::STRING_WORD,
            TestParams::ARRAY,
            TestParams::ARRAY_EMPTY,
            TestParams::ARRAY_ASSOC,
            TestParams::BOOLEAN,
            TestParams::NULL,
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
            TestParams::INT_COMMA,
            TestParams::FLOAT,
            TestParams::STRING_INT,
            TestParams::STRING_INT_COMMA,
            TestParams::STRING_FLOAT,
            TestParams::STRING_WORD,
            TestParams::ARRAY,
            TestParams::ARRAY_EMPTY,
            TestParams::ARRAY_ASSOC,
            TestParams::BOOLEAN,
            TestParams::NULL,
        ));

        foreach (array_merge($suites_ok, $suites_ng) as $suite) {
            $this->assertEquals($suite[0], Validator::isInteger($suite[1]), json_encode($suite));
        }
    }

    public function testIsString()
    {
        $suites_ok = TestTools::attachExpect(true, array_merge(
            TestParams::STRING_INT,
            TestParams::STRING_INT_COMMA,
            TestParams::STRING_FLOAT,
            TestParams::STRING_WORD,
        ));

        $suites_ng = TestTools::attachExpect(false, array_merge(
            TestParams::INT,
            TestParams::INT_COMMA,
            TestParams::FLOAT,
            TestParams::ARRAY,
            TestParams::ARRAY_EMPTY,
            TestParams::ARRAY_ASSOC,
            TestParams::BOOLEAN,
            TestParams::NULL,
        ));

        foreach (array_merge($suites_ok, $suites_ng) as $suite) {
            $this->assertEquals($suite[0], Validator::isString($suite[1]), json_encode($suite));
        }
    }

    public function testIsArray()
    {
        $suites_ok = TestTools::attachExpect(true, array_merge(
            TestParams::ARRAY,
            TestParams::ARRAY_EMPTY,
            TestParams::ARRAY_ASSOC,
        ));

        $suites_ng = TestTools::attachExpect(false, array_merge(
            TestParams::INT,
            TestParams::INT_COMMA,
            TestParams::FLOAT,
            TestParams::STRING_INT,
            TestParams::STRING_INT_COMMA,
            TestParams::STRING_FLOAT,
            TestParams::STRING_WORD,
            TestParams::BOOLEAN,
            TestParams::NULL,
        ));

        foreach (array_merge($suites_ok, $suites_ng) as $suite) {
            $this->assertEquals($suite[0], Validator::isArray($suite[1]), json_encode($suite));
        }
    }

    public function testIsArrayStrict()
    {
        $suites_ok = TestTools::attachExpect(true, array_merge(
            TestParams::ARRAY,
            TestParams::ARRAY_EMPTY
        ));

        $suites_ng = TestTools::attachExpect(false, array_merge(
            TestParams::ARRAY_ASSOC,
        ));

        foreach (array_merge($suites_ok, $suites_ng) as $suite) {
            $this->assertEquals($suite[0], Validator::isArray($suite[1], true, false), json_encode($suite));
            $this->assertNotEquals($suite[0], Validator::isArray($suite[1], true, true), json_encode($suite));
        }
    }

    public function testMinNumber()
    {
        $suites = [
            // expect, var, limit, gt
            [false, 3,   5, false],
            [false, 4,   5, false],
            [false, 5,   5, false],
            [true,  5.1, 5, false],
            [true,  6,   5, false],
            [true,  7,   5, false],

            [false, 3,   5, true],
            [false, 4,   5, true],
            [false, 4.9, 5, true],
            [true,  5,   5, true],
            [true,  6,   5, true],
            [true,  7,   5, true],
        ];

        foreach ($suites as $suite) {
            $this->assertEquals($suite[0], Validator::minNumber($suite[1], $suite[2], $suite[3]), json_encode($suite));
        }
    }


    public function testMaxNumber()
    {
        $suites = [
            // expect, var, limit, gt
            [true,  3,   5, false],
            [true,  4,   5, false],
            [true,  4.9, 5, false],
            [false, 5,   5, false],
            [false, 6,   5, false],
            [false, 7,   5, false],

            [true,  3,   5, true],
            [true,  4,   5, true],
            [true,  5,   5, true],
            [false, 5.1, 5, true],
            [false, 6,   5, true],
            [false, 7,   5, true],
        ];

        foreach ($suites as $suite) {
            $this->assertEquals($suite[0], Validator::maxNumber($suite[1], $suite[2], $suite[3]), json_encode($suite));
        }
    }
}
