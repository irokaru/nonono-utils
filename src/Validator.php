<?php

namespace nonono;

/**
 * @package nonono\Validator
 */
class Validator
{
    /**
     * @var object
     */
    protected $_data = [];

    /**
     * @var array
     */
    protected $_rules = [];

    /**
     * @var array
     */
    protected static $_types = [
        'int'     => 'static::is_number',
        'integer' => 'static::is_number',
        'numelic' => 'static::is_numelic',
        'string'  => 'static::is_string',
        'array'   => 'static::is_array',
    ];

    // -------------------------------------------------------------

    /**
     * @param array $data
     * @return Validator
     */
    public function __construct(array $data)
    {
        $this->_data = $data;

        return $this;
    }

    // -------------------------------------------------------------

    /**
     * @param mixed $var
     * @return bool
     */
    public static function isNumber($var): bool
    {
        if (is_string($var) || is_bool($var)) {
            return false;
        }

        return gettype(filter_var($var, FILTER_VALIDATE_FLOAT)) === 'double' ? true : false;
    }

    /**
     * @param mixed $var
     * @return bool
     */
    public static function isInteger($var): bool
    {
        if (!static::isNumber($var)) {
            return false;
        }

        return is_int($var);
    }

    /**
     * @param mixed $var
     * @return bool
     */
    public static function isString($var): bool
    {
        return is_string($var);
    }

    /**
     * @param mixed $var
     * @param bool  $strict
     * @param bool  $assoc
     * @return bool
     */
    public static function isArray($var, bool $strict = false, bool $assoc = false): bool
    {
        if (!is_array($var)) {
            return false;
        }

        if ($strict) {
            $all_integer = true;

            foreach (array_keys($var) as $key) {
                if (!static::isInteger($key)) {
                    if ($assoc) {
                        $all_integer = false;
                    } else {
                        return false;
                    }
                }
            }

            if ($assoc && $all_integer) {
                return false;
            }
        }

        return true;
    }

    /**
     * check var greater limit
     * @param int|float $var
     * @param int|float $limit
     * @param bool      $gt
     * @return bool
     */
    public static function minNumber(float $var, float $limit, bool $gt = true): bool
    {
        return $gt ? ($var >= $limit) : ($var > $limit);
    }

    /**
     * check var lesser limit
     * @param int|float $var
     * @param int|float $limit
     * @param bool      $gt
     * @return bool
     */
    public static function maxNumber(float $var, float $limit, bool $lt = true): bool
    {
        return $lt ? ($var <= $limit) : ($var < $limit);
    }

    /**
     * check var between limit
     * @param float     $var
     * @param int|float $min
     * @param int|float $max
     * @param bool      $gt
     * @param bool      $lt
     * @return bool
     */
    public static function betweenNumber(float $var, float $min, float $max, bool $gt = true, bool $lt = true): bool
    {
        return static::minNumber($var, $min, $gt) && static::maxNumber($var, $max, $lt);
    }

    /**
     * check var length greater limit
     * @param string $var
     * @param int    $limit
     * @param bool   $gt
     * @return bool
     */
    public static function minLength(string $var, int $limit, bool $gt = true): bool
    {
        $len = strlen($var);
        return $gt ? ($len >= $limit) : ($len > $limit);
    }

    /**
     * check var length lesser limit
     * @param string $var
     * @param int    $limit
     * @param bool   $gt
     * @return bool
     */
    public static function maxLength(string $var, int $limit, bool $lt = true): bool
    {
        $len = strlen($var);
        return $lt ? ($len <= $limit) : ($len < $limit);
    }

    /**
     * check var length between
     * @param string $var
     * @param int    $min
     * @param int    $max
     * @param bool   $gt
     * @param bool   $lt
     * @return bool
     */
    public static function betweenLength(string $var, int $min, int $max, bool $gt = true, bool $lt = true): bool
    {
        return static::minLength($var, $min, $gt) && static::maxLength($var, $max, $lt);
    }

    /**
     * check var length greater limit
     * @param array $var
     * @param int   $limit
     * @param bool  $gt
     * @return bool
     */
    public static function minArrayLength(array $var, int $limit, bool $gt = true): bool
    {
        $len = count($var);
        return $gt ? ($len >= $limit) : ($len > $limit);
    }

    /**
     * check var length lesser limit
     * @param array $var
     * @param int   $limit
     * @param bool  $gt
     * @return bool
     */
    public static function maxArrayLength(array $var, int $limit, bool $lt = true): bool
    {
        $len = count($var);
        return $lt ? ($len <= $limit) : ($len < $limit);
    }

    /**
     * check var length between
     * @param float $var
     * @param int   $min
     * @param int   $max
     * @param bool  $gt
     * @param bool  $lt
     * @return bool
     */
    public static function betweenArrayLength(array $var, int $min, int $max, bool $gt = true, bool $lt = true): bool
    {
        return static::minArrayLength($var, $min, $gt) && static::maxArrayLength($var, $max, $lt);
    }
}
