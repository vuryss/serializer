<?php

declare(strict_types=1);

use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\Tests\Datasets\DiscriminatorMap\Type1;
use Vuryss\Serializer\Tests\Datasets\DiscriminatorMap\Type2;
use Vuryss\Serializer\Tests\Datasets\DiscriminatorMap\WrapperClass;

test(
    'Can determine right class with discriminator map',
    function () {
        $serializer = new Serializer();

        $data = [
            'disc' => ['key' => 'type1', 'property' => 'some string'],
            'disc2' => ['key' => 'type2', 'property' => 123],
        ];
        $instance = $serializer->deserialize(json_encode($data), WrapperClass::class);

        expect($instance)
            ->toBeInstanceOf(WrapperClass::class)
            ->and($instance->disc)->toBeInstanceOf(Type1::class)
            ->and($instance->disc->key)->toBe('type1')
            ->and($instance->disc->property)->toBe('some string')
            ->and($instance->disc2)->toBeInstanceOf(Type2::class)
            ->and($instance->disc2->key)->toBe('type2')
            ->and($instance->disc2->property)->toBe(123);

        $data = [
            'disc' => ['key' => 'type2', 'property' => 123],
            'disc2' => ['key' => 'type1', 'property' => 'some string'],
        ];
        $instance = $serializer->deserialize(json_encode($data), WrapperClass::class);

        expect($instance)
            ->toBeInstanceOf(WrapperClass::class)
            ->and($instance->disc)->toBeInstanceOf(Type2::class)
            ->and($instance->disc->key)->toBe('type2')
            ->and($instance->disc->property)->toBe(123)
            ->and($instance->disc2)->toBeInstanceOf(Type1::class)
            ->and($instance->disc2->key)->toBe('type1')
            ->and($instance->disc2->property)->toBe('some string');
    }
);

test(
    'Cannot determine right class with discriminator map for invalid mapped value',
    function () {
        $serializer = new Serializer();

        $data = [
            'disc' => ['key' => 'type3', 'property' => 'some string'],
            'disc2' => ['key' => 'type2', 'property' => 123],
        ];
        $serializer->deserialize(json_encode($data), WrapperClass::class);
    }
)
->throws(
    DeserializationImpossibleException::class,
    'Cannot denormalize data at path "$.disc" into interface because none of the mapped types match the value "type3"'
);

test(
    'Cannot determine right class with discriminator map for invalid value for given key',
    function () {
        $serializer = new Serializer();

        $data = [
            'disc' => ['key2' => 'type1', 'property' => 'some string'],
            'disc2' => ['key' => 'type2', 'property' => 123],
        ];
        $serializer->deserialize(json_encode($data), WrapperClass::class);
    }
)
->throws(
    DeserializationImpossibleException::class,
    'Cannot denormalize data at path "$.disc" into interface because no matching type map was found'
);
