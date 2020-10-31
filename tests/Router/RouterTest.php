<?php

namespace nonono\Router;

use nonono\Lib\TestTools;

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testValidateRequestMethod()
    {
        $r = new Router();

        $suites = [
            // expect, actual, request
            [true,  'GET', 'GET'],
            [false, 'GET', 'POST'],
            [false, 'GET', 'PUT'],
            [false, 'GET', 'DELETE'],

            [false, 'POST', 'GET'],
            [true,  'POST', 'POST'],
            [false, 'POST', 'PUT'],
            [false, 'POST', 'DELETE'],

            [false, 'PUT', 'GET'],
            [false, 'PUT', 'POST'],
            [true,  'PUT', 'PUT'],
            [false, 'PUT', 'DELETE'],

            [false, 'DELETE', 'GET'],
            [false, 'DELETE', 'POST'],
            [false, 'DELETE', 'PUT'],
            [true,  'DELETE', 'DELETE'],
        ];

        foreach ($suites as $suite) {
            $_SERVER['REQUEST_METHOD'] = $suite[1];

            $this->assertEquals(
                $suite[0],
                TestTools::getProtectedMethod(Router::class, '_validateRequestMethod')->invoke($r, $suite[2]),
                json_encode($suite)
            );
        }
    }

    public function testValidateMethodException()
    {
        $r = new Router();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid request method');

        $suites = [
            'get', 'post', 'put', 'delete', 'hoge',
        ];

        foreach ($suites as $suite) {
            TestTools::getProtectedMethod(Router::class, '_validateRequestMethod')->invoke($r, $suite[2]);
        }
    }

    public function testRequestmethod()
    {
        $r = new Router();

        $suites = [
            'HOGE', 'GET', 'PUT',
        ];

        foreach ($suites as $suite) {
            $_SERVER['REQUEST_METHOD'] = $suite;

            $this->assertEquals(
                $suite,
                TestTools::getProtectedMethod(Router::class, '_requestMethod')->invoke($r, $suite),
                json_encode($suite)
            );
        }

        unset($_SERVER['REQUEST_METHOD']);
        $this->assertEquals('', TestTools::getProtectedMethod(Router::class, '_requestMethod')->invoke($r, $suite));
    }
}
