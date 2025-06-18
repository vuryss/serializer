<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Unit\Metadata;

use Mockery;
use Symfony\Component\TypeInfo\Type;

use Symfony\Component\TypeInfo\Type\BuiltinType as SymfonyBuiltinType;
use Symfony\Component\TypeInfo\Type\NullableType;
use Symfony\Component\TypeInfo\Type\ObjectType;
use Symfony\Component\TypeInfo\Type\UnionType;
use Symfony\Component\TypeInfo\TypeIdentifier;
use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Exception\MetadataExtractionException;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\TypeMapper;

/**
 * @property TypeMapper $typeMapper
 * @property SerializerContext $serializerContext
 */
beforeEach(function () {
    $this->typeMapper = new TypeMapper();
    $this->serializerContext = new SerializerContext();
});

afterEach(function () {
    Mockery::close();
});

test('map types with nullable type adds null type', function () {
    $type = new NullableType(new SymfonyBuiltinType(TypeIdentifier::STRING));

    $dataTypes = $this->typeMapper->mapTypes(
        type: $type,
        serializerContext: $this->serializerContext
    );

    expect($dataTypes)->toHaveCount(2)
        ->and($dataTypes[0]->type)->toBe(BuiltInType::NULL)
        ->and($dataTypes[1]->type)->toBe(BuiltInType::STRING);
});

test('map types with explicit null type does not add duplicate null', function () {
    $type = new NullableType(new UnionType(
        new SymfonyBuiltinType(TypeIdentifier::STRING),
        new SymfonyBuiltinType(TypeIdentifier::INT),
    ));

    $dataTypes = $this->typeMapper->mapTypes(
        type: $type,
        serializerContext: $this->serializerContext
    );

    expect($dataTypes)->toHaveCount(3)
        ->and($dataTypes[0]->type)->toBe(BuiltInType::NULL);

    $remainingTypes = [
        $dataTypes[1]->type,
        $dataTypes[2]->type,
    ];

    expect($remainingTypes)->toContain(BuiltInType::STRING)
        ->and($remainingTypes)->toContain(BuiltInType::INTEGER);
});

test('map to internal type throws unsupported type for callable', function () {
    $type = new SymfonyBuiltinType(TypeIdentifier::CALLABLE);

    $this->typeMapper->mapTypes(
        type: $type,
        serializerContext: $this->serializerContext
    );
})->throws(
    MetadataExtractionException::class,
    'Unsupported built-in type: callable'
);

test('map to internal type throws unsupported type for resource', function () {
    $type = new SymfonyBuiltinType(TypeIdentifier::RESOURCE);

    $this->typeMapper->mapTypes(
        type: $type,
        serializerContext: $this->serializerContext
    );
})->throws(
    MetadataExtractionException::class,
    'Unsupported built-in type: resource'
);

test('map object type for interface with overwrite', function () {
    $type = new ObjectType(\DateTimeInterface::class);
    $dataTypes = $this->typeMapper->mapTypes(type: $type, serializerContext: $this->serializerContext);

    expect($dataTypes)->toHaveCount(1)
        ->and($dataTypes[0]->type)->toBe(BuiltInType::OBJECT)
        ->and($dataTypes[0]->className)->toBe(\DateTime::class);
});

test('map types throws exception for unknown type class', function () {
    $unknownType = Mockery::mock(Type::class);
    $expectedMessage = 'Unsupported type: ' . get_class($unknownType);

    expect(fn () => $this->typeMapper->mapTypes(
        type: $unknownType,
        serializerContext: $this->serializerContext
    ))->toThrow(MetadataExtractionException::class, $expectedMessage);
});

test('map object type for generic object', function () {
    $type = new SymfonyBuiltinType(TypeIdentifier::OBJECT);
    $dataTypes = $this->typeMapper->mapTypes(type: $type, serializerContext: $this->serializerContext);

    expect($dataTypes)->toHaveCount(1)
        ->and($dataTypes[0]->type)->toBe(BuiltInType::OBJECT)
        ->and($dataTypes[0]->className)->toBeNull();
});
