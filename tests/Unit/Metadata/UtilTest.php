<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

test(
    'Cannot use a class that does not exist',
    function () {
        \Vuryss\Serializer\Metadata\Util::reflectionClass('InvalidClassName');
    }
)
->throws(
    \Vuryss\Serializer\Exception\MetadataExtractionException::class,
    'Class "InvalidClassName" does not exist.',
);

test(
    'Cannot reflect an invalid property in class',
    function () {
        $reflectionClass = \Vuryss\Serializer\Metadata\Util::reflectionClass(\Vuryss\Serializer\Tests\Datasets\InvalidClassReference::class);
        \Vuryss\Serializer\Metadata\Util::reflectionProperty($reflectionClass, 'invalidPropertyName');
    }
)
->throws(
    \Vuryss\Serializer\Exception\MetadataExtractionException::class,
    'Property "invalidPropertyName" of class "Vuryss\Serializer\Tests\Datasets\InvalidClassReference" does not exist.',
);
