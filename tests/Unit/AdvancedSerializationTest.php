<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

test('Support for advanced denormalizers', function () {
    $serializer = new \Vuryss\Serializer\Serializer(
        normalizers: [
            new \Vuryss\Serializer\Tests\Datasets\BuiltInObjectTypeNormalizer(),
            new \Vuryss\Serializer\Normalizer\DateTimeNormalizer(),
        ],
        denormalizers: [
            new \Vuryss\Serializer\Tests\Datasets\BuiltInObjectTypeDenormalizer(),
            new \Vuryss\Serializer\Denormalizer\DateTimeDenormalizer(),
        ]
    );

    $data = [
        '#type' => \Vuryss\Serializer\Tests\Datasets\Person::class,
        'firstName' => 'Someone',
        'lastName' => 'WithLastName',
        'age' => 76,
        'isStudent' => false,
    ];

    $person = $serializer->deserialize(json_encode($data));

    expect($person)
        ->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\Person::class)
        ->firstName->toBe('Someone')
        ->lastName->toBe('WithLastName')
        ->age->toBe(76)
        ->isStudent->toBeFalse()
    ;

    $serialized = $serializer->serialize($person);

    expect($serialized)->toBe(json_encode($data));

});
