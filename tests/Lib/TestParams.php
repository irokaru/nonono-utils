<?php

namespace nonono\Lib;

class TestParams
{
    public const INT = [
        10000, -10000,
    ];

    public const INT_COMMA = [
        100.00, -100.00,
    ];

    public const FLOAT = [
        123.45, -123.45,
    ];

    public const STRING_INT = [
        '10000', '-10000',
    ];

    public const STRING_INT_COMMA = [
        '100.00', '-100.00',
    ];

    public const STRING_FLOAT = [
        '123.45', '-123.45',
    ];

    public const STRING_WORD = [
        'hogehoge',
    ];

    public const ARRAY = [
        ['a', 'b', 'c']
    ];

    public const ARRAY_EMPTY = [
        []
    ];

    public const ARRAY_ASSOC = [
        ['key' => 'hoge']
    ];

    public const BOOLEAN = [
        true, false,
    ];

    public const BOOLEAN_TRUE = [
        true,
    ];

    public const BOOLEAN_FALSE = [
        false,
    ];

    public const NULL = [
        null
    ];
}
