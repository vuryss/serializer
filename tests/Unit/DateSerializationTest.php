<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace Vuryss\Serializer\Unit;

use Vuryss\Serializer\Context;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Exception\InvalidAttributeUsageException;
use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\SerializerInterface;
use Vuryss\Serializer\Tests\Datasets\Dates;
use Vuryss\Serializer\Tests\Datasets\InvalidDateFormatProperty;

use function Pest\Faker\fake;

test('Dates are deserialized and serialized correctly', function () {
    $serializer = new Serializer(
        context: [
            Context::DATETIME_FORMAT => \DateTimeInterface::RFC2822,
        ]
    );

    $date1 = fake()->dateTime();
    $date2 = fake()->dateTime();
    $date3 = fake()->dateTime();
    $date4 = fake()->dateTime();
    $date5 = fake()->dateTime();

    $data = [
        'uglyUsaDate' => $date1->format('m/d/Y'),
        'immutableDate' => $date2->format('Y-m-d'),
        'dateTimeFormat1' => $date3->format(\DateTimeInterface::RFC3339_EXTENDED),
        'globalDateTimeFormat' => $date4->format(\DateTimeInterface::RFC2822),
        'interfaceDateTime' => $date5->format(\DateTimeInterface::RFC3339_EXTENDED),
    ];

    $dates = $serializer->deserialize(json_encode($data), Dates::class, SerializerInterface::FORMAT_JSON);

    expect($dates->uglyUsaDate)->toBeInstanceOf(\DateTime::class)
        ->and($dates->uglyUsaDate->format('m/d/Y'))->toBe($date1->format('m/d/Y'))
        ->and($dates->immutableDate)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($dates->immutableDate->format('Y-m-d'))->toBe($date2->format('Y-m-d'))
        ->and($dates->dateTimeFormat1)->toBeInstanceOf(\DateTime::class)
        ->and($dates->dateTimeFormat1->format(\DateTimeInterface::RFC3339_EXTENDED))->toBe($date3->format(\DateTimeInterface::RFC3339_EXTENDED))
        ->and($dates->globalDateTimeFormat)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($dates->globalDateTimeFormat->format(\DateTimeInterface::RFC2822))->toBe($date4->format(\DateTimeInterface::RFC2822))
        ->and($dates->interfaceDateTime)->toBeInstanceOf(\DateTime::class)
        ->and($dates->interfaceDateTime->format(\DateTimeInterface::RFC3339_EXTENDED))->toBe($data['interfaceDateTime'])
    ;

    $data['interfaceDateTime'] = $date5->format(\DateTimeInterface::RFC2822);

    $json = $serializer->serialize($dates, SerializerInterface::FORMAT_JSON);

    expect($json)->json()->toBe($data);
});

test('Cannot deserialize dates with invalid format', function () {
    $serializer = new Serializer();

    $date2 = fake()->dateTime();
    $date3 = fake()->dateTime();
    $date4 = fake()->dateTime();

    $data = [
        'uglyUsaDate' => '2024-05-21',
        'immutableDate' => $date2->format('Y-m-d'),
        'dateTimeFormat1' => $date3->format(\DateTimeInterface::RFC3339_EXTENDED),
        'globalDateTimeFormat' => $date4->format(\DateTimeInterface::RFC2822),
    ];

    $serializer->deserialize(json_encode($data), Dates::class, SerializerInterface::FORMAT_JSON);
})->throws(
    DeserializationImpossibleException::class,
    'Cannot denormalize date string "2024-05-21" at path "$.uglyUsaDate" into DateTimeImmutable. Expected format: "m/d/Y"'
);

test('Cannot deserialize into invalid format even with fallback', function () {
    $serializer = new Serializer();

    $date2 = fake()->dateTime();
    $date3 = fake()->dateTime();
    $date4 = fake()->dateTime();

    $data = [
        'uglyUsaDate' => $date2->format('m/d/Y'),
        'immutableDate' => 'invalid-date',
        'dateTimeFormat1' => $date3->format(\DateTimeInterface::RFC3339_EXTENDED),
        'globalDateTimeFormat' => $date4->format(\DateTimeInterface::RFC2822),
    ];

    $serializer->deserialize(json_encode($data), Dates::class, SerializerInterface::FORMAT_JSON);
})->throws(
    DeserializationImpossibleException::class,
    'Cannot denormalize date string "invalid-date" at path "$.immutableDate" into DateTimeImmutable. Expected format: "Y-m-d"'
);

test('Can deserialize dates with fallback to support all date formats', function () {
    $serializer = new Serializer(
        context: [
            Context::DATETIME_FORMAT => \DateTimeInterface::RFC2822,
        ]
    );

    $date1 = fake()->dateTime();
    $date2 = fake()->dateTime();

    $data = [
        'uglyUsaDate' => $date1->format('m/d/Y'),
        'immutableDate' => $date2->format(\DateTimeInterface::COOKIE),
    ];

    $dates = $serializer->deserialize(json_encode($data), Dates::class, SerializerInterface::FORMAT_JSON);

    expect($dates->uglyUsaDate)->toBeInstanceOf(\DateTime::class)
        ->and($dates->uglyUsaDate->format('m/d/Y'))->toBe($date1->format('m/d/Y'))
        ->and($dates->immutableDate)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($dates->immutableDate->format('Y-m-d'))->toBe($date2->format('Y-m-d'))
    ;
});

test('Cannot accept invalid date format', function () {
    $serializer = new Serializer(
        context: [
            Context::DATETIME_FORMAT => 123,
        ]
    );

    $date1 = fake()->dateTime();
    $date2 = fake()->dateTime();
    $date3 = fake()->dateTime();
    $date4 = fake()->dateTime();

    $data = [
        'uglyUsaDate' => $date1->format('m/d/Y'),
        'immutableDate' => $date2->format('Y-m-d'),
        'dateTimeFormat1' => $date3->format(\DateTimeInterface::RFC3339_EXTENDED),
        'globalDateTimeFormat' => $date4->format(\DateTimeInterface::RFC2822),
    ];

    $serializer->deserialize(json_encode($data), Dates::class, SerializerInterface::FORMAT_JSON);
})->throws(
    InvalidAttributeUsageException::class,
    'DateTime format attribute must be a string'
);

test('Cannot serialize with invalid date format', function () {
    $serializer = new Serializer();
    $invalidObject = new InvalidDateFormatProperty();
    $invalidObject->someDate = fake()->dateTime();

    $serializer->serialize($invalidObject, SerializerInterface::FORMAT_JSON);
})->throws(
    InvalidAttributeUsageException::class,
    'DateTime format attribute must be a string',
);
