<?php

declare(strict_types=1);

use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\SerializerInterface;
use Vuryss\Serializer\Tests\Datasets\TypeSequence\WrapperClass;

test(
    'Types are denormalized in sequence of their declaration',
    function () {
        $serializer = new Serializer();
        $data = [
            'property' => [
                ['make' => 'Ford'],
                ['make' => 'Chevrolet'],
            ],
            'property2' => [
                ['make' => 'Ram'],
                ['make' => 'Toyota'],
            ],
        ];

        $instance = $serializer->deserialize(json_encode($data), WrapperClass::class, SerializerInterface::FORMAT_JSON);

        expect($instance->property)->toBeArray()
            ->and($instance->property[0])->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\TypeSequence\Car::class)
            ->and($instance->property[1])->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\TypeSequence\Car::class)
            ->and($instance->property2)->toBeArray()
            ->and($instance->property2[0])->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\TypeSequence\Truck::class)
            ->and($instance->property2[1])->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\TypeSequence\Truck::class);
    }
);
