<?php

$finder = PhpCsFixer\Finder::create()
    ->notName('README.md')
    ->notName('.php_cs')
    ->notName('composer.*')
    ->notName('phpunit.xml*')
    ->notName('*.xml')
    ->exclude('vendor')
    ->exclude('plugin')
    ->exclude('tmp')
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@PHP71Migration' => true,
        'array_syntax' => ['syntax' => 'short'],
        'function_typehint_space' => true,
        'no_unused_imports' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_whitespace_before_comma_in_array' => true,
        // 'ordered_imports' => true,
        'return_type_declaration' => true,
        'ternary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setUsingCache(false)
    ->setFinder($finder);