<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace Vuryss\Serializer\Unit;

use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Exception\InvalidAttributeUsageException;
use Vuryss\Serializer\Serializer;

use Vuryss\Serializer\SerializerException;
use Vuryss\Serializer\SerializerInterface;
use Vuryss\Serializer\Tests\Datasets\Dates;

use Vuryss\Serializer\Tests\Datasets\InvalidDateFormatProperty;

use function Pest\Faker\fake;

test('Dates are deserialized and serialized correctly', function () {
    $serializer = new Serializer(
        attributes: [
            SerializerInterface::ATTRIBUTE_DATETIME_FORMAT => \DateTimeInterface::RFC2822,
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

    $dates = $serializer->deserialize(json_encode($data), Dates::class);

    expect($dates->uglyUsaDate)->toBeInstanceOf(\DateTime::class)
        ->and($dates->uglyUsaDate->format('m/d/Y'))->toBe($date1->format('m/d/Y'))
        ->and($dates->immutableDate)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($dates->immutableDate->format('Y-m-d'))->toBe($date2->format('Y-m-d'))
        ->and($dates->dateTimeFormat1)->toBeInstanceOf(\DateTime::class)
        ->and($dates->dateTimeFormat1->format(\DateTimeInterface::RFC3339_EXTENDED))->toBe($date3->format(\DateTimeInterface::RFC3339_EXTENDED))
        ->and($dates->globalDateTimeFormat)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($dates->globalDateTimeFormat->format(\DateTimeInterface::RFC2822))->toBe($date4->format(\DateTimeInterface::RFC2822));

    $json = $serializer->serialize($dates);

    expect($json)->json()->toBe($data);
});

test('Cannot deserialize dates with invalid format', function () {
    $serializer = new Serializer();

    $date2 = fake()->dateTime();
    $date3 = fake()->dateTime();
    $date4 = fake()->dateTime();

    $data = [
        'uglyUsaDate' => '2021-01-01',
        'immutableDate' => $date2->format('Y-m-d'),
        'dateTimeFormat1' => $date3->format(\DateTimeInterface::RFC3339_EXTENDED),
        'globalDateTimeFormat' => $date4->format(\DateTimeInterface::RFC2822),
    ];

    $serializer->deserialize(json_encode($data), Dates::class);
})->throws(
    DeserializationImpossibleException::class,
    'Cannot denormalize date string "2021-01-01" at path "$.uglyUsaDate" into DateTimeImmutable. Expected format: "m/d/Y"'
);

test('Cannot accept invalid date format', function () {
    $serializer = new Serializer(
        attributes: [
            SerializerInterface::ATTRIBUTE_DATETIME_FORMAT => 123,
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

    $serializer->deserialize(json_encode($data), Dates::class);
})->throws(
    InvalidAttributeUsageException::class,
    'DateTime format attribute must be a string'
);

test('Cannot serialize with invalid date format', function () {
    $serializer = new Serializer();
    $invalidObject = new InvalidDateFormatProperty();
    $invalidObject->someDate = fake()->dateTime();

    $serializer->serialize($invalidObject);
})->throws(
    InvalidAttributeUsageException::class,
    'DateTime format attribute must be a string',
);
