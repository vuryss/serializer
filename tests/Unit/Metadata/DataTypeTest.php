<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Unit\Metadata;

use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Exception\UnsupportedType;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Tests\Datasets\Person;

test('DataType::fromData returns correct type for built-ins', function (mixed $data, BuiltInType $expectedBuiltInType) {
    $dataType = DataType::fromData(data: $data);
    expect($dataType->type)->toBe($expectedBuiltInType)
        ->and($dataType->className)->toBeNull()
        ->and($dataType->listType)->toBe([]);
})->with([
    [null, BuiltInType::NULL],
    [true, BuiltInType::BOOLEAN],
    [1, BuiltInType::INTEGER],
    [1.5, BuiltInType::FLOAT],
    ['string', BuiltInType::STRING],
    [['array'], BuiltInType::ARRAY],
]);

test('DataType::fromData returns correct type for existing class', function () {
    $person = new Person();
    $dataType = DataType::fromData(data: $person);
    expect($dataType->type)->toBe(BuiltInType::OBJECT)
        ->and($dataType->className)->toBe(Person::class)
        ->and($dataType->listType)->toBe([]);
});

test('DataType::fromData throws exception for resource', function () {
    $resource = fopen('php://memory', 'r');
    DataType::fromData(data: $resource);
})->throws(DeserializationImpossibleException::class, 'Resource type is not supported');

test('DataType::fromUserType returns correct type for built-ins', function (string $typeString, BuiltInType $expectedBuiltInType) {
    $dataType = DataType::fromUserType(type: $typeString);
    expect($dataType->type)->toBe($expectedBuiltInType)
        ->and($dataType->className)->toBeNull()
        ->and($dataType->listType)->toBe([]);
})->with([
    ['null', BuiltInType::NULL],
    ['boolean', BuiltInType::BOOLEAN],
    ['integer', BuiltInType::INTEGER],
    ['float', BuiltInType::FLOAT],
    ['string', BuiltInType::STRING],
    ['array', BuiltInType::ARRAY],
    ['mixed', BuiltInType::MIXED],
]);

test('DataType::fromUserType returns correct type for existing class', function () {
    $dataType = DataType::fromUserType(type: Person::class);
    expect($dataType->type)->toBe(BuiltInType::OBJECT)
        ->and($dataType->className)->toBe(Person::class)
        ->and($dataType->listType)->toBe([]);
});

test('DataType::fromUserType returns correct type for array of built-in', function () {
    $dataType = DataType::fromUserType(type: 'integer[]');
    expect($dataType->type)->toBe(BuiltInType::ARRAY)
        ->and($dataType->className)->toBeNull();

    expect($dataType->listType)->toHaveCount(1);
    $subType = $dataType->listType[0];
    expect($subType->type)->toBe(BuiltInType::INTEGER);
});

test('DataType::fromUserType returns correct type for array of class', function () {
    $dataType = DataType::fromUserType(type: Person::class . '[]');
    expect($dataType->type)->toBe(BuiltInType::ARRAY)
        ->and($dataType->className)->toBeNull();

    expect($dataType->listType)->toHaveCount(1);
    $subType = $dataType->listType[0];
    expect($subType->type)->toBe(BuiltInType::OBJECT)
        ->and($subType->className)->toBe(Person::class);
});

test('DataType::fromUserType returns correct type for generic array', function () {
    $dataType = DataType::fromUserType(type: '[]');
    expect($dataType->type)->toBe(BuiltInType::ARRAY)
        ->and($dataType->className)->toBeNull()
        ->and($dataType->listType)->toBe([]);
});

test('DataType::fromUserType throws exception for unsupported type', function () {
    DataType::fromUserType(type: 'UnsupportedTypeString');
})->throws(UnsupportedType::class, 'Unsupported type: UnsupportedTypeString');

test('DataType::fromUserType throws exception for unsupported array subtype', function () {
    DataType::fromUserType(type: 'UnsupportedTypeString[]');
})->throws(UnsupportedType::class, 'Unsupported type: UnsupportedTypeString');
