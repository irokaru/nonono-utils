<?php

namespace nonono\Router;

use nonono\Lib\TestTools;

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testGetAndPost()
    {
        $r = new Router();

        $suites = [
            //expect, request
            ['this is aaa',   '/aaa'],
            ['bbb desuyo.',   '/bbb'],
            ['/aaa/bbb dayo', '/aaa/bbb'],
            ['param page',    '/aaa/hoge'],
        ];

        foreach ($suites as $suite) {
            TestTools::access('http://example.com' . $suite[1], 'GET');

            ob_start();
            Router::get('/aaa', 'this is aaa');
            Router::get('/bbb', 'bbb desuyo.');
            Router::get('/aaa/bbb', '/aaa/bbb dayo');
            Router::get('/aaa/{param}', 'param page');
            $result = ob_get_clean();

            $this->assertEquals($suite[0], $result, json_encode($suite));

            TestTools::getProtectedProperty(Router::class, '_viewed')->setValue($r, false);
        }

        foreach ($suites as $suite) {
            TestTools::access('http://example.com' . $suite[1], 'POST');

            ob_start();
            Router::post('/aaa', 'this is aaa');
            Router::post('/bbb', 'bbb desuyo.');
            Router::post('/aaa/bbb', '/aaa/bbb dayo');
            Router::post('/aaa/{param}', 'param page');
            $result = ob_get_clean();

            $this->assertEquals($suite[0], $result, json_encode($suite));

            TestTools::getProtectedProperty(Router::class, '_viewed')->setValue($r, false);
        }
    }

    public function testGetException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('path must be slash at the beginning');

        $suites = [
            'hoge', 'hoge/', 'a/a/a/',
        ];

        $_SERVER['REQUEST_METHOD'] = 'GET';

        foreach ($suites as $suite) {
            Router::get($suite, 'exception');
        }
    }

    public function testMatchPath()
    {
        $r = new Router();

        $suites = [
            //expect, path, script_name
            [true, '/test', '/test'],
            [true, '/hoge/{param}', '/hoge/123'],
            [true, '/hoge/{param}', '/hoge/'],

            [false, '/test', '/test/'],
            [false, '/aaaa', '/bbbb'],
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

    public function testCheckParam()
    {
        $r = new Router();

        $suites = [
            //expect, path
            [true, '{test}'],
            [false, '{test'],
            [false, 'test}'],
            [false, '}test{'],
        ];

        foreach ($suites as $suite) {
            $this->assertEquals(
                $suite[0],
                TestTools::getProtectedMethod(Router::class, '_checkParam')->invoke($r, $suite[1]),
                json_encode($suite),
            );
        }
    }

    public function testRequest()
    {
        $r = new Router();

        $suites = [
            '/test',
            '/hoge/aaa',
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
