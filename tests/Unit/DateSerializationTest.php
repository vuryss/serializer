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
use Vuryss\Serializer\Tests\Datasets\DatesWithTimezoneProperty;
use Vuryss\Serializer\Tests\Datasets\DatesWithInvalidTimezoneProperty;

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
    'Cannot denormalize date string "2024-05-21" at path "$.uglyUsaDate" into DateTime. Expected format: "m/d/Y"'
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

test('Dates are deserialized with global target timezone', function () {
    $targetTimezone = 'America/New_York';
    $serializer = new Serializer(
        context: [
            Context::DATETIME_TARGET_TIMEZONE => $targetTimezone,
        ]
    );

    $dateString = '2024-05-22T10:00:00+00:00'; // UTC
    $expectedDateTime = new \DateTimeImmutable($dateString);
    $expectedDateTimeInNewYork = $expectedDateTime->setTimezone(new \DateTimeZone($targetTimezone));

    $data = [
        'immutableDate' => $dateString,
    ];

    $dates = $serializer->deserialize(json_encode($data), Dates::class, SerializerInterface::FORMAT_JSON);

    expect($dates->immutableDate)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($dates->immutableDate->getTimezone()->getName())->toBe($targetTimezone)
        ->and($dates->immutableDate->format(\DateTimeInterface::RFC3339))->toBe($expectedDateTimeInNewYork->format(\DateTimeInterface::RFC3339));
});

test('Dates are deserialized with per-property target timezone', function () {
    $targetTimezoneProperty = 'Europe/Berlin';
    $serializer = new Serializer();

    // Original date in UTC
    $dateStringUtc = '2024-05-22T12:00:00+00:00';
    $utcDateTime = new \DateTimeImmutable($dateStringUtc);

    // Expected date in Europe/Berlin for 'dateTimeFormat1'
    $expectedBerlinDateTime = $utcDateTime->setTimezone(new \DateTimeZone($targetTimezoneProperty));

    // For 'globalDateTimeFormat', no specific timezone is set on property, so it should remain as parsed (or use global if set)
    // In this test, no global timezone is set, so it should parse as is (likely UTC or system default depending on string)
    $dateStringForGlobal = '2024-05-23T15:00:00+02:00'; // A string with timezone info
    $expectedGlobalDateTime = new \DateTimeImmutable($dateStringForGlobal);


    $data = [
        'dateTimeFormat1' => $dateStringUtc, // This will be converted
        'globalDateTimeFormat' => $dateStringForGlobal, // This will use its own timezone or default if no TZ in string
    ];

    // We need a class with the SerializerContext attribute for dateTimeFormat1

    $dates = $serializer->deserialize(json_encode($data), DatesWithTimezoneProperty::class, SerializerInterface::FORMAT_JSON);

    expect($dates->dateTimeFormat1)->toBeInstanceOf(\DateTime::class) // Default for DateTimeInterface is DateTime
        ->and($dates->dateTimeFormat1->getTimezone()->getName())->toBe($targetTimezoneProperty)
        ->and($dates->dateTimeFormat1->format(\DateTimeInterface::RFC3339))->toBe($expectedBerlinDateTime->format(\DateTimeInterface::RFC3339))
        ->and($dates->globalDateTimeFormat)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($dates->globalDateTimeFormat->getTimezone()->getName())->toBe($expectedGlobalDateTime->getTimezone()->getName())
        ->and($dates->globalDateTimeFormat->format(\DateTimeInterface::RFC3339))->toBe($expectedGlobalDateTime->format(\DateTimeInterface::RFC3339));
});

test('Date deserialization without target timezone works as before', function () {
    $serializer = new Serializer();
    $dateString = '2024-05-22T10:00:00+05:00'; // A date string with a specific timezone
    $expectedDateTime = new \DateTimeImmutable($dateString);

    $data = [
        'immutableDate' => $dateString,
    ];

    $dates = $serializer->deserialize(json_encode($data), Dates::class, SerializerInterface::FORMAT_JSON);

    expect($dates->immutableDate)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($dates->immutableDate->getTimezone()->getName())->toBe($expectedDateTime->getTimezone()->getName())
        ->and($dates->immutableDate->format(\DateTimeInterface::RFC3339))->toBe($expectedDateTime->format(\DateTimeInterface::RFC3339));
});

test('Throws exception for invalid target timezone string globally', function () {
    $serializer = new Serializer(
        context: [
            Context::DATETIME_TARGET_TIMEZONE => 'Invalid/Timezone',
        ]
    );

    $data = ['immutableDate' => '2024-05-22T10:00:00Z'];
    $serializer->deserialize(json_encode($data), Dates::class, SerializerInterface::FORMAT_JSON);
})->throws(
    DeserializationImpossibleException::class,
    'Invalid target timezone string "Invalid/Timezone" at path "$.immutableDate"'
);

test('Throws exception for invalid target timezone string on property', function () {
    $serializer = new Serializer();

    $data = ['dateTimeFormat1' => '2024-05-22T10:00:00Z'];
    $serializer->deserialize(json_encode($data), DatesWithInvalidTimezoneProperty::class, SerializerInterface::FORMAT_JSON);

})->throws(
    DeserializationImpossibleException::class,
    'Invalid target timezone string "Another/InvalidZone" at path "$.dateTimeFormat1"'
);
