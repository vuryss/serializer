<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\Tests\Datasets\TypeMap\MissingTypeMap;
use Vuryss\Serializer\Tests\Datasets\TypeMap\PropertyTypeMap;
use Vuryss\Serializer\Tests\Datasets\TypeMap\PropertyTypeMapImplementation1;
use Vuryss\Serializer\Tests\Datasets\TypeMap\PropertyTypeMapImplementation2;

test('Missing type map fails deserialization', function () {
    $json = '{"prop":{}}';
    $serializer = new Serializer();
    $serializer->deserialize($json, MissingTypeMap::class);
})->throws(
    \Vuryss\Serializer\Exception\MetadataExtractionException::class,
    'Class "Vuryss\Serializer\Tests\Datasets\TypeMap\MissingTypeMapInterface" does not have a valid DiscriminatorMap attribute. Cannot resolve data type of abstract class or interface.',
);

test('Type map with multiple properties', function () {
    $data = ['prop' => ['prop1' => 'value1']];
    $serializer = new Serializer();
    $instance = $serializer->deserialize(json_encode($data), PropertyTypeMap::class);
    expect($instance->prop)->toBeInstanceOf(PropertyTypeMapImplementation1::class)
        ->and($instance->prop->prop1)->toBe('value1');

    $data = ['prop' => ['prop2' => 'value2']];
    $instance = $serializer->deserialize(json_encode($data), PropertyTypeMap::class);
    expect($instance->prop)->toBeInstanceOf(PropertyTypeMapImplementation2::class)
        ->and($instance->prop->prop2)->toBe('value2');
});
