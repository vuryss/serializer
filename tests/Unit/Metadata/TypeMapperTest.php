<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Unit\Metadata;

use Mockery;
use Mockery\MockInterface;
use ReflectionProperty;
use Symfony\Component\PropertyInfo\Type;
use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Exception\UnsupportedType;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\TypeMapper;
use Vuryss\Serializer\Tests\Datasets\Person; // A sample class for reflection

///**
// * @property TypeMapper $typeMapper
// * @property ReflectionProperty|MockInterface $mockReflectionProperty
// * @property SerializerContext $mockSerializerContext
// */
//beforeEach(function () {
//    $this->typeMapper = new TypeMapper();
//    $this->mockReflectionProperty = Mockery::mock(ReflectionProperty::class);
//    $this->mockReflectionProperty->shouldReceive('getName')->andReturn('testProperty');
//
//    $declaringClassMock = Mockery::mock(\ReflectionClass::class);
//    $declaringClassMock->shouldReceive('getName')->andReturn(Person::class);
//    $this->mockReflectionProperty->shouldReceive('getDeclaringClass')->andReturn($declaringClassMock);
//
//    $this->mockSerializerContext = new SerializerContext();
//});
//
//afterEach(function () {
//    Mockery::close();
//});
//
//test('map types with nullable type adds null type', function () {
//    $propertyInfoType = new Type(
//        builtinType: Type::BUILTIN_TYPE_STRING,
//        nullable: true
//    );
//
//    $dataTypes = $this->typeMapper->mapTypes(
//        type: [$propertyInfoType],
//        reflectionProperty: $this->mockReflectionProperty,
//        serializerContext: $this->mockSerializerContext
//    );
//
//    expect($dataTypes)->toHaveCount(2)
//        ->and($dataTypes[0]->type)->toBe(BuiltInType::STRING)
//        ->and($dataTypes[1]->type)->toBe(BuiltInType::NULL);
//});
//
//test('map types with explicit null type does not add duplicate null', function () {
//    $propertyInfoTypes = [
//        new Type(builtinType: Type::BUILTIN_TYPE_STRING, nullable: false),
//        new Type(builtinType: Type::BUILTIN_TYPE_NULL, nullable: true), // Nullable flag on NULL type itself
//    ];
//
//    $dataTypes = $this->typeMapper->mapTypes(
//        type: $propertyInfoTypes,
//        reflectionProperty: $this->mockReflectionProperty,
//        serializerContext: $this->mockSerializerContext
//    );
//
//    expect($dataTypes)->toHaveCount(2)
//        ->and($dataTypes[0]->type)->toBe(BuiltInType::STRING)
//        ->and($dataTypes[1]->type)->toBe(BuiltInType::NULL);
//});
//
//test('map to internal type throws unsupported type for callable', function () {
//    $propertyInfoType = new Type(builtinType: Type::BUILTIN_TYPE_CALLABLE);
//
//    // Internal method, but we test its contract via mapTypes
//    $this->typeMapper->mapTypes(
//        type: [$propertyInfoType],
//        reflectionProperty: $this->mockReflectionProperty,
//        serializerContext: $this->mockSerializerContext
//    );
//})->throws(
//    UnsupportedType::class,
//    'Property "testProperty" of class "Vuryss\Serializer\Tests\Datasets\Person" has an unsupported type: callable'
//);
//
//test('map to internal type throws unsupported type for unknown built in', function () {
//    // Mocking Type class to simulate an unknown built-in type
//    $propertyInfoType = Mockery::mock(Type::class);
//    $propertyInfoType->shouldReceive('getBuiltinType')->andReturn('unknown_symfony_type');
//    $propertyInfoType->shouldReceive('isNullable')->andReturn(false);
//    $propertyInfoType->shouldReceive('getClassName')->andReturn(null);
//    $propertyInfoType->shouldReceive('getCollectionValueTypes')->andReturn([]);
//
//    $this->typeMapper->mapTypes(
//        type: [$propertyInfoType],
//        reflectionProperty: $this->mockReflectionProperty,
//        serializerContext: $this->mockSerializerContext
//    );
//})->throws(
//    UnsupportedType::class,
//    'Property "testProperty" of class "Vuryss\Serializer\Tests\Datasets\Person" has an unsupported type: unknown_symfony_type'
//);
//
//test('map object type for interface with overwrite', function () {
//    $propertyInfoType = new Type(builtinType: Type::BUILTIN_TYPE_OBJECT, nullable: false, class: \DateTimeInterface::class);
//    $dataTypes = $this->typeMapper->mapTypes([$propertyInfoType], $this->mockReflectionProperty, $this->mockSerializerContext);
//
//    expect($dataTypes)->toHaveCount(1)
//        ->and($dataTypes[0]->type)->toBe(BuiltInType::OBJECT)
//        ->and($dataTypes[0]->className)->toBe(\DateTime::class);
//});
//
//test('map object type for generic object', function () {
//    // Test for Type::BUILTIN_TYPE_OBJECT with no class name (generic object)
//    $propertyInfoType = new Type(builtinType: Type::BUILTIN_TYPE_OBJECT, nullable: false, class: null);
//    $dataTypes = $this->typeMapper->mapTypes([$propertyInfoType], $this->mockReflectionProperty, $this->mockSerializerContext);
//
//    expect($dataTypes)->toHaveCount(1)
//        ->and($dataTypes[0]->type)->toBe(BuiltInType::OBJECT)
//        ->and($dataTypes[0]->className)->toBeNull(); // Expecting generic object
//});
