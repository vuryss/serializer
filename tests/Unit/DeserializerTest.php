<?php

/** @noinspection PhpUnhandledExceptionInspection */

use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Exception\MetadataExtractionException;
use Vuryss\Serializer\Tests\Datasets\Complex1\Airbag;
use Vuryss\Serializer\Tests\Datasets\Complex1\Car;
use Vuryss\Serializer\Tests\Datasets\Complex1\Engine;
use Vuryss\Serializer\Tests\Datasets\Complex1\FuelType;
use Vuryss\Serializer\Tests\Datasets\InvalidEnumWrapper;
use Vuryss\Serializer\Tests\Datasets\InvalidIntersectionTypeWrapper;

test('Deserializing into data structures', function ($expected, $serialized) {
    $serializer = new \Vuryss\Serializer\Serializer();

    expect($serializer->deserialize($serialized))->toBe($expected);
})->with([
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
]);

test('Deserializing object, skipping optional arguments in constructor', function () {
    $serializer = new \Vuryss\Serializer\Serializer();

    $monitor = $serializer->deserialize(
        json_encode(['make' => 'Asus', 'size' => 24]),
        \Vuryss\Serializer\Tests\Datasets\Monitor::class
    );

    expect($monitor)->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\Monitor::class)
        ->and($monitor->make)->toBe('Asus')
        ->and($monitor->is4k)->toBeTrue()
        ->and($monitor->size)->toBe(24);
});

test('Deserializing array of objects', function () {
    $serialized = '[{"firstName":"John","lastName":"Doe","age":25,"isStudent":true},{"firstName":"Maria","lastName":"Valentina","age":36,"isStudent":false}]';

    $serializer = new \Vuryss\Serializer\Serializer();

    $data = $serializer->deserialize($serialized, \Vuryss\Serializer\Tests\Datasets\Person::class . '[]');

    expect($data)->toBeArray()->toHaveCount(2)
        ->and($data[0])->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\Person::class)
        ->and($data[0]->firstName)->toBe('John')
        ->and($data[0]->lastName)->toBe('Doe')
        ->and($data[0]->age)->toBe(25)
        ->and($data[0]->isStudent)->toBeTrue()
        ->and($data[1])->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\Person::class)
        ->and($data[1]->firstName)->toBe('Maria')
        ->and($data[1]->lastName)->toBe('Valentina')
        ->and($data[1]->age)->toBe(36)
        ->and($data[1]->isStudent)->toBeFalse();
});

test('Deserializing empty array type', function () {
    $serialized = '[{"firstName":"John","lastName":"Doe","age":25,"isStudent":true},{"firstName":"Maria","lastName":"Valentina","age":36,"isStudent":false}]';

    $serializer = new \Vuryss\Serializer\Serializer();

    $data = $serializer->deserialize($serialized, '[]');

    expect($data)->toBeArray()->toHaveCount(2)
        ->and($data[0]['firstName'])->toBe('John')
        ->and($data[0]['lastName'])->toBe('Doe')
        ->and($data[0]['age'])->toBe(25)
        ->and($data[0]['isStudent'])->toBeTrue()
        ->and($data[1]['firstName'])->toBe('Maria')
        ->and($data[1]['lastName'])->toBe('Valentina')
        ->and($data[1]['age'])->toBe(36)
        ->and($data[1]['isStudent'])->toBeFalse();
});

test('Complex deserialization & serialization', function () {
    $serializer = new \Vuryss\Serializer\Serializer();
    $object = $serializer->deserialize(Car::getJsonSerialized(), Car::class);

    expect($object)->toBeInstanceOf(Car::class)
        ->and($object->licensePlate)->toBe('Y6492AH')
        ->and($object->getHorsePower())->toBe(150)
        ->and($object->isReleased)->toBeTrue()
        ->and($object->weight)->toBe(1500.5)
        ->and($object->height)->toBe(123)
        ->and($object->multiValueField)->toBeInstanceOf(Engine::class)
        ->and($object->controlModules)->toBeArray()
        ->and($object->controlModules)->toHaveCount(2)
        ->and($object->controlModules[0])->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\Complex1\ClimateControlModule::class)
        ->and($object->controlModules[0]->maxTemperature)->toBe(30)
        ->and($object->controlModules[1])->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\Complex1\EngineControlModule::class)
        ->and($object->controlModules[1]->fuelType)->toBe(FuelType::HYBRID)
    ;

    $engine = $object->multiValueField;

    expect($engine)->toBeInstanceOf(Engine::class)
        ->and($engine->getCylinders())->toBe(4)
        ->and($engine->getCode())->toBe('VTEC')
        ->and($engine->fuelType)->toBe(FuelType::DIESEL)
        ->and($engine->multiValueField)->toBeArray();

    foreach ($engine->multiValueField as $string) {
        expect($string)->toBeString();
    }

    $engine = $object->getEngine();

    expect($engine)->toBeInstanceOf(Engine::class)
        ->and($engine->getCylinders())->toBe(8)
        ->and($engine->getCode())->toBe('V8')
        ->and($engine->fuelType)->toBe(FuelType::PETROL)
        ->and($engine->multiValueField)->toBeArray();

    foreach ($engine->multiValueField as $enum) {
        expect($enum)->toBe(FuelType::DIESEL);
    }

    $airbags = $object->airbags;

    expect($airbags)->toBeArray()->toHaveCount(2);

    foreach ($airbags as $airbag) {
        expect($airbag)->toBeInstanceOf(Airbag::class)
            ->and($airbag->model)->toBeString();
    }

    $string = $serializer->serialize($object);

    expect($string)->json()->toMatchArray(json_decode(Car::getJsonSerialized(), true));
});

test('Deserializing with custom set of denormalizers', function () {
    $serializer = new \Vuryss\Serializer\Serializer(
        denormalizers: [
            new \Vuryss\Serializer\Denormalizer\BasicTypesDenormalizer(),
            new \Vuryss\Serializer\Denormalizer\ObjectDenormalizer(),
        ]
    );

    $object = $serializer->deserialize('{"firstName":"John","lastName":"Doe","age":25,"isStudent":true}', \Vuryss\Serializer\Tests\Datasets\Person::class);

    expect($object)->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\Person::class)
        ->and($object->firstName)->toBe('John')
        ->and($object->lastName)->toBe('Doe')
        ->and($object->age)->toBe(25)
        ->and($object->isStudent)->toBeTrue();
});

test('Cannot deserialize from invalid json', function () {
    $serializer = new \Vuryss\Serializer\Serializer();

    $serializer->deserialize('{"firstName":"John","lastName":"Doe","age":25,"isStudent":true', \Vuryss\Serializer\Tests\Datasets\Person::class);
})->throws(\Vuryss\Serializer\Exception\EncodingException::class, 'Failed to decode JSON data');

test('Deserializing into mixed value field', function (mixed $expected, string $serialized) {
    $serializer = new \Vuryss\Serializer\Serializer();
    $object = $serializer->deserialize($serialized, \Vuryss\Serializer\Tests\Datasets\MixedValues::class);

    expect($object)
        ->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\MixedValues::class)
        ->and($object->mixedValue)
        ->toBe($expected);
})->with([
    'int' => [654, '{"mixedValue":654}'],
    'float' => [654.321, '{"mixedValue":654.321}'],
    'string' => ['string', '{"mixedValue":"string"}'],
    'bool' => [true, '{"mixedValue":true}'],
    'null' => [null, '{"mixedValue":null}'],
    'array' => [['array', 'of', 'values'], '{"mixedValue":["array","of","values"]}'],
    'object' => [['key' => 'value'], '{"mixedValue":{"key":"value"}}'],
]);

test('Deserialize with explicit basic type', function () {
    $serializer = new \Vuryss\Serializer\Serializer();

    $result = $serializer->deserialize('123', 'integer');
    expect($result)->toBe(123);
});

test('Cannot deserialize resource', function () {
    $serializer = new \Vuryss\Serializer\Serializer();
    $opendir = opendir(sys_get_temp_dir());
    $serializer->denormalize($opendir, null);
    closedir($opendir);
})->throws(DeserializationImpossibleException::class, 'Resource type is not supported');

test('Cannot deserialize if none of the values matches the union type declared', function () {
    $serializer = new \Vuryss\Serializer\Serializer();
    $serializer->deserialize('{"value":[]}', \Vuryss\Serializer\Tests\Datasets\MultipleTypes::class);
})->throws(DeserializationImpossibleException::class, 'Cannot denormalize value "array" at path "$.value" into any of the given types');

test(
    'Cannot deserialize into non-existing class',
    function() {
        $serializer = new \Vuryss\Serializer\Serializer();
        $serializer->deserialize('{"value":123}', \Vuryss\Serializer\Tests\Datasets\InvalidClassReference::class);
    }
)
->throws(
    MetadataExtractionException::class,
    'Class "Vuryss\Serializer\Tests\Datasets\InvalidClassName" does not exist.'
);


test(
    'Cannot deserialize into non-backed enum',
    function() {
        $serializer = new \Vuryss\Serializer\Serializer();
        $serializer->deserialize('{"property":"string"}', InvalidEnumWrapper::class);
    }
)
->throws(
    MetadataExtractionException::class,
    'Non-backed enum type "Vuryss\Serializer\Tests\Datasets\NonBackedEnum" is not supported. Use a backed enum instead.'
);

test(
    'Cannot deserialize into intersection type',
    function() {
        $serializer = new \Vuryss\Serializer\Serializer();
        $serializer->deserialize('{"value":123}', InvalidIntersectionTypeWrapper::class);
    }
)
->throws(
    MetadataExtractionException::class,
    'Intersection type "Vuryss\Serializer\Tests\Datasets\Monitor&Vuryss\Serializer\Tests\Datasets\Person" is not supported.'
);
