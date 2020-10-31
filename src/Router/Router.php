<?php

namespace nonono\Router;

/**
 * @package nonono\Router
 */
class Router
{
    /**
     * @param string $request
     * @return bool
     */
    protected static function _validateRequestMethod(string $request): bool
    {
        $keys = ['GET', 'POST', 'PUT', 'DELETE'];

        if (!in_array($request, $keys, true)) {
            throw new \InvalidArgumentException('invalid request method');
        }

        return $_SERVER['REQUEST_METHOD'] === $request;
    }

    /**
     * @return string
     */
    protected static function _requestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? '';
    }
}
