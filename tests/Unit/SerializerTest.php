<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\Tests\Datasets\ClassWithNestedClass;
use Vuryss\Serializer\Tests\Datasets\Person;
use Vuryss\Serializer\Tests\Datasets\SerializedName;

test('Serializing data structures', function ($data, $expected) {
    $serializer = new Serializer();
    expect($serializer->serialize($data))->toBe($expected);
})->with(
    [
        [null, 'null'],
        [true, 'true'],
        [false, 'false'],
        [1, '1'],
        [1.1, '1.1'],
        ['string', '"string"'],
        [['list', 'of', 'data', false, true, null, 1, 1.2], '["list","of","data",false,true,null,1,1.2]'],
        [['key' => 'value'], '{"key":"value"}'],
        [['key' => ['nested' => 'value']], '{"key":{"nested":"value"}}'],
        [['key' => ['nested' => ['deeply' => 'nested']]], '{"key":{"nested":{"deeply":"nested"}}}'],
        [new Person(), '{"firstName":"John","lastName":"Doe","age":25,"isStudent":true}'],
        [new SerializedName(), '{"changedPropertyName":"value"}'],
        [new ClassWithNestedClass(), '{"person":{"firstName":"John","lastName":"Doe","age":25,"isStudent":true},"nonNestedProperty":"nonNestedProperty"}'],
    ]
);

test('Serializer with null values', function () {
    $serializer = new Serializer(
        attributes: [
            \Vuryss\Serializer\SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES => false,
        ]
    );

    $data = new \Vuryss\Serializer\Tests\Datasets\NullValues();
    $expected = json_encode([
        'nullableString' => null,
        'alwaysEnabledNull' => null,
        'nullableInt' => null,
    ]);

    expect($serializer->serialize($data))->toBe($expected);
});

test('Serializer without null values', function () {
    $serializer = new Serializer(
        attributes: [
            \Vuryss\Serializer\SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES => true,
        ]
    );

    $data = new \Vuryss\Serializer\Tests\Datasets\NullValues();
    $expected = json_encode([
        'alwaysEnabledNull' => null,
    ]);

    expect($serializer->serialize($data))->toBe($expected);
});

test('Serializing array of objects', function () {
    $serializer = new Serializer();

    $person1 = new Person();

    $person2 = new Person();
    $person2->firstName = 'Maria';
    $person2->lastName = 'Valentina';
    $person2->age = 36;
    $person2->isStudent = false;


    $data = [$person1, $person2];

    $serialized = $serializer->serialize($data);

    expect($serialized)->toBe('[{"firstName":"John","lastName":"Doe","age":25,"isStudent":true},{"firstName":"Maria","lastName":"Valentina","age":36,"isStudent":false}]');
});

test('Serializing of mixed values', function ($value, $serialized) {
    $serializer = new Serializer();

    $mixedValueObject = new \Vuryss\Serializer\Tests\Datasets\MixedValues();
    $mixedValueObject->mixedValue = 123;

    $serialized = $serializer->serialize($mixedValueObject);

    expect($serialized)->toBe('{"mixedValue":123}');
})->with([
    'int' => [123, '{"mixedValue":123}'],
    'float' => [123.45, '{"mixedValue":123.45}'],
    'string' => ['string', '{"mixedValue":"string"}'],
    'bool' => [true, '{"mixedValue":true}'],
    'null' => [null, '{"mixedValue":null}'],
    'array' => [[1, 2, 3], '{"mixedValue":[1,2,3]}'],
    'object' => [new stdClass(), '{"mixedValue":{}}'],
    'object with properties' => [(object) ['property' => 'value'], '{"mixedValue":{"property":"value"}}'],
]);
