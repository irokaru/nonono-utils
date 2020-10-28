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
    public function __construct(array $data): Validator
    {
        $this->_data = $data;

        return $this;
    }
}
