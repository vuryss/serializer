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

test('Complex deserialization', function () {
    $serializer = new \Vuryss\Serializer\Serializer();
    $object = $serializer->deserialize(Car::getJsonSerialized(), Car::class);

    expect($object)->toBeInstanceOf(Car::class)
        ->and($object->licensePlate)->toBe('Y6492AH')
        ->and($object->getHorsePower())->toBe(150)
        ->and($object->isReleased)->toBeTrue()
        ->and($object->weight)->toBe(1500.5)
        ->and($object->height)->toBe(123);

    $engine = $object->getEngine();

    expect($engine)->toBeInstanceOf(Engine::class)
        ->and($engine->getCylinders())->toBe(4)
        ->and($engine->getCode())->toBe('V8')
        ->and($engine->fuelType)->toBe(FuelType::PETROL);

    $airbags = $object->airbags;

    expect($airbags)->toBeArray()->toHaveCount(2);

    foreach ($airbags as $airbag) {
        expect($airbag)->toBeInstanceOf(Airbag::class)
            ->and($airbag->model)->toBeString();
    }
});
