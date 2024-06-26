<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('vendor')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS2.0' => true,
        '@PHP83Migration' => true,
        'no_unused_imports' => true,
    ])
    ->setFinder($finder)
;
