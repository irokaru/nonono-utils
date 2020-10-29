<?php

namespace nonono\Lib;

class TestParams
{
    const INT = [
        10000, -10000,
    ];

    const INT_COMMA = [
        100.00, -100.00,
    ];

    const FLOAT = [
        123.45, -123.45,
    ];

    const STRING_INT = [
        '10000', '-10000',
    ];

    const STRING_INT_COMMA = [
        '100.00', '-100.00',
    ];

    const STRING_FLOAT = [
        '123.45', '-123.45',
    ];

    const STRING_WORD = [
        'hogehoge',
    ];

    const ARRAY = [
        ['a', 'b', 'c']
    ];

    const ARRAY_EMPTY = [
        []
    ];

    const OBJECT = [
        ['key' => 'hoge']
    ];

    const BOOLEAN = [
        true, false,
    ];

    const BOOLEAN_TRUE = [
        true,
    ];

    const BOOLEAN_FALSE = [
        false,
    ];

    const NULL = [
        null
    ];
}
