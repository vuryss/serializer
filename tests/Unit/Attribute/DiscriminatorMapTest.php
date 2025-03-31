<?php

declare(strict_types=1);

use Vuryss\Serializer\Attribute\DiscriminatorMap;
use Vuryss\Serializer\Exception\InvalidAttributeUsageException;

test(
    'Cannot use DiscriminatorMap attribute without type property',
    function (): void {
        new DiscriminatorMap('', ['test' => DateTime::class]);
    },
)->throws(InvalidAttributeUsageException::class);

test(
    'Cannot use DiscriminatorMap attribute with empty map',
    function (): void {
        new DiscriminatorMap('test', []);
    },
)->throws(InvalidAttributeUsageException::class);
