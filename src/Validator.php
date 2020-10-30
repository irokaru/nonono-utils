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
    protected static $_r = [
        'type' => [
            'int'         => 'static::isInteger',
            'integer'     => 'static::isInteger',
            'numelic'     => 'static::isNumelic',
            'string'      => 'static::isString',
            'array'       => 'static::isArray',
            'assoc_array' => 'static::isAssocArray',
        ],

        'min' => [
            'int'         => 'static::minNumber',
            'integer'     => 'static::minNumber',
            'numelic'     => 'static::minNumber',
            'string'      => 'static::minLength',
            'array'       => 'static::minArrayLength',
            'assoc_array' => 'static::minArrayLength',
        ],

        'max' => [
            'int'         => 'static::maxNumber',
            'integer'     => 'static::maxNumber',
            'numelic'     => 'static::maxNumber',
            'string'      => 'static::maxLength',
            'array'       => 'static::maxArrayLength',
            'assoc_array' => 'static::maxArrayLength',
        ],
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

    /**
     * @param string $key
     * @param array  $rules
     * @return Validator
     */
    public function rules(string $key, array $rules)
    {
        if (!static::minLength($key, 1)) {
            throw new \InvalidArgumentException('key length is greater than 1');
        }

        if (static::_checkRules($rules)) {
            throw new \InvalidArgumentException('invalid rule');
        }

        $this->_rules[$key] = $rules;

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
     * @param mixed $var
     * @return bool
     */
    public static function isAssocArray($var): bool
    {
        return static::isArray($var, true, true);
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
     * @param array $var
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

    // -------------------------------------------------------------

    /**
     * @return array
     */
    protected static function _getRules(): array
    {
        return array_keys(static::$_r);
    }

    /**
     * @return array
     */
    protected static function _getTypes(): array
    {
        return array_keys(static::$_r['type']);
    }

    /**
     * @param array $rules
     * @return bool
     */
    protected static function _checkRules(array $rules): bool
    {
        return (bool) count(array_diff(static::_getRules(), array_keys($rules)));
    }
}
