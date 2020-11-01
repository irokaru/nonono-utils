<?php

namespace nonono\Router;

/**
 * @package nonono\Router
 */
class Router
{
    /**
     * @param string $path
     * @param string $view
     * @return void
     */
    public static function get(string $path, string $view)
    {
        if (!static::_validateRequestMethod('GET')) {
            return;
        }

        if (!static::_validatePath($path)) {
            throw new \InvalidArgumentException('path must be slash at the beginning');
        }

        if (!static::_matchPath($path)) {
            return;
        }

        print($view);

        return;
    }

    // -------------------------------------------------------------

    /**
     * @param string $path
     * @return bool
     */
    protected static function _matchPath(string $path): bool
    {
        $path_dir   = explode('/', $path);
        $script_dir = explode('/', static::_request());

        if (count($path_dir) !== count($script_dir)) {
            return false;
        }

        for ($inx = 0; $inx < count($script_dir); $inx++) {
            if (preg_match('/^{(.+?)}$/', $path_dir)) {
                continue;
            }

            if ($path_dir !== $script_dir) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $path
     * @return bool
     */
    protected static function _validatePath(string $path): bool
    {
        return preg_match('/^\//', $path) !== false;
    }

    /**
     * @param string $request GET | POST | PUT | DELETE
     * @return bool
     */
    protected static function _validateRequestMethod(string $request): bool
    {
        $keys = ['GET', 'POST', 'PUT', 'DELETE'];

        if (!in_array($request, $keys, true)) {
            throw new \InvalidArgumentException('invalid request method');
        }

        return static::_requestMethod() === $request;
    }

    /**
     * @return string
     */
    protected static function _request(): string
    {
        return $_SERVER['SCRIPT_NAME'];
    }

    /**
     * @return string
     */
    protected static function _requestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? '';
    }
}
