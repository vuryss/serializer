<?php

/** @noinspection PhpUnhandledExceptionInspection */

use Vuryss\Serializer\Tests\Datasets\Complex1\Airbag;
use Vuryss\Serializer\Tests\Datasets\Complex1\Car;
use Vuryss\Serializer\Tests\Datasets\Complex1\Engine;
use Vuryss\Serializer\Tests\Datasets\Complex1\FuelType;

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
