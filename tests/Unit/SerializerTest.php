<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

use Vuryss\Serializer\Context;
use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\SerializerInterface;
use Vuryss\Serializer\Tests\Datasets\ClassWithNestedClass;
use Vuryss\Serializer\Tests\Datasets\MultipleSerializerContext;
use Vuryss\Serializer\Tests\Datasets\Person;
use Vuryss\Serializer\Tests\Datasets\SerializedName;
use Vuryss\Serializer\Tests\Datasets\UntypedProperty;

test('Serializing data structures', function ($data, $expected) {
    $serializer = new Serializer();
    expect($serializer->serialize($data, SerializerInterface::FORMAT_JSON))->toBe($expected);
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
        context: [
            Context::SKIP_NULL_VALUES => false,
        ]
    );

    $data = new \Vuryss\Serializer\Tests\Datasets\NullValues();
    $expected = json_encode([
        'nullableString' => null,
        'alwaysEnabledNull' => null,
        'nullValue' => null,
        'nullableInt' => null,
    ]);

    expect($serializer->serialize($data, SerializerInterface::FORMAT_JSON))->toBe($expected);
});

test('Serializer without null values', function () {
    $serializer = new Serializer(
        context: [
            Context::SKIP_NULL_VALUES => true,
        ]
    );

    $data = new \Vuryss\Serializer\Tests\Datasets\NullValues();
    $expected = json_encode([
        'alwaysEnabledNull' => null,
    ]);

    expect($serializer->serialize($data, SerializerInterface::FORMAT_JSON))->toBe($expected);
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

    $serialized = $serializer->serialize($data, SerializerInterface::FORMAT_JSON);

    expect($serialized)->toBe('[{"firstName":"John","lastName":"Doe","age":25,"isStudent":true},{"firstName":"Maria","lastName":"Valentina","age":36,"isStudent":false}]');
});

test('Serializer with subset of normalizers', function () {
    $serializer = new Serializer(
        normalizers: [
            new \Vuryss\Serializer\Normalizer\BasicTypesNormalizer(),
            new \Vuryss\Serializer\Normalizer\ObjectNormalizer(),
        ]
    );

    $person = new Person();
    $person->firstName = 'John';
    $person->lastName = 'Doe';
    $person->age = 25;
    $person->isStudent = true;

    $serialized = $serializer->serialize($person, SerializerInterface::FORMAT_JSON);

    expect($serialized)->toBe('{"firstName":"John","lastName":"Doe","age":25,"isStudent":true}');
});

test('Cannot serialize resources', function () {
    $serializer = new Serializer();
    $resource = fopen(__FILE__, 'r');

    $serializer->serialize($resource, SerializerInterface::FORMAT_JSON);
})->throws(
    \Vuryss\Serializer\Exception\NormalizerNotFoundException::class,
    'No normalizer found for the given data: resource (stream)'
);

test('Serializing of mixed values', function ($value, $expectedSerialized) {
    $serializer = new Serializer();

    $mixedValueObject = new \Vuryss\Serializer\Tests\Datasets\MixedValues();
    $mixedValueObject->mixedValue = $value;

    $serialized = $serializer->serialize($mixedValueObject, SerializerInterface::FORMAT_JSON);

    expect($serialized)->toBe($expectedSerialized);
})->with([
    'int' => [123, '{"mixedValue":123}'],
    'float' => [123.45, '{"mixedValue":123.45}'],
    'string' => ['string', '{"mixedValue":"string"}'],
    'bool' => [true, '{"mixedValue":true}'],
    'null' => [null, '{"mixedValue":null}'],
    'array' => [[1, 2, 3], '{"mixedValue":[1,2,3]}'],
    'object' => [new stdClass(), '{"mixedValue":[]}'],
    'object with properties' => [(object) ['property' => 'value'], '{"mixedValue":{"property":"value"}}'],
]);

test('Cannot serialize untyped properties', function () {
    $serializer = new Serializer();
    $object = new UntypedProperty();
    $object->property = 'value';

    $serializer->serialize($object, SerializerInterface::FORMAT_JSON);
})->throws(
    \Vuryss\Serializer\Exception\MetadataExtractionException::class,
    'Unable to resolve type for property "property" of class "Vuryss\Serializer\Tests\Datasets\UntypedProperty".'
);

test('Multiple serializer context attributes are not supported', function () {
    $serializer = new Serializer();
    $object = new MultipleSerializerContext();

    $serializer->serialize($object, SerializerInterface::FORMAT_JSON);
})->throws(
    \Vuryss\Serializer\Exception\InvalidAttributeUsageException::class,
    'Property "name" of class "Vuryss\Serializer\Tests\Datasets\MultipleSerializerContext" has more than one SerializerContext attribute'
);

test('Serializer respects json-normalizable interface', function () {
    $serializer = new Serializer();
    $object = new \Vuryss\Serializer\Tests\Datasets\SampleJsonSerializable();

    $result = $serializer->serialize($object, SerializerInterface::FORMAT_JSON);

    expect($result)->toBe('{"some-key":"some-value","other-key":123,"nested":{"nested-key":"nested-value"}}');
});

test('Cannot serialize with unsupported format', function () {
    $serializer = new Serializer();
    $serializer->serialize('data', 'unsupported_format');
})->throws(
    \Vuryss\Serializer\Exception\UnsupportedFormatException::class,
    'Unsupported format "unsupported_format". Only "json" is supported.'
);

test('Cannot deserialize with unsupported format', function () {
    $serializer = new Serializer();
    $serializer->deserialize('data', 'string', 'unsupported_format');
})->throws(
    \Vuryss\Serializer\Exception\UnsupportedFormatException::class,
    'Unsupported format "unsupported_format". Only "json" is supported.'
);

test('Cannot deserialize non-string data', function () {
    $serializer = new Serializer();
    $serializer->deserialize(data: 123, type: 'string', format: SerializerInterface::FORMAT_JSON);
})->throws(
    \Vuryss\Serializer\Exception\UnsupportedFormatException::class,
    'Expected string data, got "int"'
);

test('Throws encoding exception for invalid UTF-8 characters during serialize', function () {
    $serializer = new Serializer();
    // Simulate data that becomes invalid for json_encode after normalization
    // This requires a bit of a setup, as basic types normalizer might handle it.
    // Let's use a class with a public property.
    $data = new class {
        public string $value = "\xB1\x31"; // Invalid UTF-8 sequence
    };
    $serializer->serialize($data, SerializerInterface::FORMAT_JSON);
})->throws(
    \Vuryss\Serializer\Exception\EncodingException::class,
    'Failed to encode data to JSON'
);
