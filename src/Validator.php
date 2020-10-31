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
            'numelic'     => 'static::isNumber',
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

    /**
     * @var array
     */
    protected static $_message = [
        'type' => [
            'int'         => '<%name>の型が不正です',
            'integer'     => '<%name>の型が不正です',
            'numelic'     => '<%name>の型が不正です',
            'string'      => '<%name>の型が不正です',
            'array'       => '<%name>の型が不正です',
            'assoc_array' => '<%name>の型が不正です',
        ],

        'min' => [
            'int'         => '<%name>は<%num>以上にしてください',
            'integer'     => '<%name>は<%num>以上にしてください',
            'numelic'     => '<%name>は<%num>以上にしてください',
            'string'      => '<%name>は<%num>文字以上にしてください',
            'array'       => '<%name>は<%num>要素以上にしてください',
            'assoc_array' => '<%name>は<%num>要素以上にしてください',
        ],

        'max' => [
            'int'         => '<%name>は<%num>以下にしてください',
            'integer'     => '<%name>は<%num>以下にしてください',
            'numelic'     => '<%name>は<%num>以下にしてください',
            'string'      => '<%name>は<%num>文字以下にしてください',
            'array'       => '<%name>は<%num>要素以下にしてください',
            'assoc_array' => '<%name>は<%num>要素以下にしてください',
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

        $filtered_rules = static::_filterRules($rules);

        if (!static::_checkRules($filtered_rules)) {
            throw new \InvalidArgumentException('invalid rule');
        }

        $this->_rules[$key] = $filtered_rules;

        return $this;
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        $err = [];

        foreach ($this->_rules as $key => $rules) {
            $type = $rules['type'];
            $name = $this->_getNameAsKey($key);

            // check type
            if (!call_user_func(static::$_r['type'][$type], $this->_getDataValueAsKey($key))) {
                $msg = static::_getMessage('type', $type, $name);
                $err = static::_setError($err, $key, $msg);
                continue;
            }

            //  check type other than
            foreach (array_slice(static::$_r, 1) as $rule_name => $methods) {
                if (!isset($rules[$rule_name])) {
                    continue;
                }

                $method = $methods[$type];

                $args = [
                    $this->_getDataValueAsKey($key),
                    $rules[$rule_name],
                ];

                if (!call_user_func_array($method, $args)) {
                    $msg = static::_getMessage($rule_name, $type, $name, $args[1]);
                    $err = static::_setError($err, $key, $msg);
                }
            }
        }

        return $err;
    }

    public function exec(): bool
    {
        return count($this->errors()) === 0;
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
     * @param string $key
     * @return mixed
     */
    protected function _getNameAsKey(string $key)
    {
        return $this->_rules[$key]['name'] ?? $key;
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function _getDataValueAsKey(string $key)
    {
        return $this->_data[$key];
    }

    // -------------------------------------------------------------

    /**
     * @return array
     */
    protected static function _getRules(): array
    {
        return array_keys(static::$_r);
    }


    protected static function _filterRules($rules): array
    {
        $filter = [];

        $filter_list = array_merge(static::_getRules(), ['name']);

        foreach ($filter_list as $key) {
            $filter[$key] = null;
        }

        return array_intersect_key($rules, $filter);
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
        $diff = array_diff(array_keys($rules), static::_getRules());

        if ($diff === 0 || !isset($rules['type'])) {
            return false;
        }

        if (!in_array($rules['type'], static::_getTypes(), true)) {
            return false;
        }

        if (isset($rules['min']) && !static::isNumber($rules['min'])) {
            return false;
        }

        if (isset($rules['max']) && !static::isNumber($rules['max'])) {
            return false;
        }

        if (isset($rules['name']) && (!static::isString($rules['name']) || !static::minLength($rules['name'], 1))) {
            return false;
        }

        return true;
    }

    /**
     * @param array  $error
     * @param string $key
     * @param string $message
     */
    protected static function _setError(array $error, string $key, string $message): array
    {
        if (!isset($error[$key])) {
            $error[$key] = [];
        }

        $error[$key][] = $message;

        return $error;
    }

    /**
     * @param string $type
     * @param string $name
     * @param float  $num
     * @return string
     */
    protected static function _getMessage(string $rule, string $type, string $name, float $num = 0): string
    {
        $message = static::$_message[$rule][$type];
        $message = str_replace(['<%name>', '<%num>'], [$name, (string) $num], $message);

        return $message;
    }
}
