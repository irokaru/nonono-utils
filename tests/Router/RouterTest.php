<?php

namespace nonono\Router;

use nonono\Lib\TestTools;

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testMatchPath()
    {
        $r = new Router();

        $suites = [
            //expect, path, script_name
            [true, '/test', '/test'],
            [true, '/hoge/{param}', '/hoge/123'],
            [true, '/hoge/{param}', '/hoge/'],

            [false, '/test', '/test/'],
        ];

        foreach ($suites as $suite) {
            $_SERVER['SCRIPT_NAME'] = $suite[2];
            $this->assertEquals(
                $suite[0],
                TestTools::getProtectedMethod(Router::class, '_matchPath')->invoke($r, $suite[1]),
                json_encode($suite),
            );
        }
    }

    public function testValidatePath()
    {
        $r = new Router();

        $suites = [
            //expect, path
            [true,  '/test'],
            [true,  '/hoge/fuga'],
            [false, 'test'],
            [false, 'hoge/fuga'],
        ];

        foreach ($suites as $suite) {
            $this->assertEquals(
                $suite[0],
                TestTools::getProtectedMethod(Router::class, '_validatePath')->invoke($r, $suite[1]),
                json_encode($suite),
            );
        }
    }

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

    public function testRequest()
    {
        $r = new Router();

        $suites = [
            '/test',
            '/hoge/aaa'
        ];

        foreach ($suites as $suite) {
            $_SERVER['SCRIPT_NAME'] = $suite;

            $this->assertEquals(
                $suite,
                TestTools::getProtectedMethod(Router::class, '_request')->invoke($r, $suite),
                json_encode($suite)
            );
        }

        unset($_SERVER['SCRIPT_NAME']);
        $this->assertEquals('', TestTools::getProtectedMethod(Router::class, '_request')->invoke($r, $suite));
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
