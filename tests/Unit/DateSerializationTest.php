<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Unit;

use Vuryss\Serializer\Serializer;

use Vuryss\Serializer\Tests\Datasets\Dates;

use function Pest\Faker\fake;

test('Dates are deserialized and serialized correctly', function () {
    $serializer = new Serializer();

    $date1 = fake()->dateTime();
    $date2 = fake()->dateTime();
    $date3 = fake()->dateTime();

    $data = [
        'uglyUsaDate' => $date1->format('m/d/Y'),
        'immutableDate' => $date2->format('Y-m-d'),
        'dateTimeFormat1' => $date3->format(\DateTimeInterface::RFC3339_EXTENDED),
    ];

    $dates = $serializer->deserialize(json_encode($data), Dates::class);

    expect($dates->uglyUsaDate)->toBeInstanceOf(\DateTime::class)
        ->and($dates->uglyUsaDate->format('m/d/Y'))->toBe($date1->format('m/d/Y'))
        ->and($dates->immutableDate)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($dates->immutableDate->format('Y-m-d'))->toBe($date2->format('Y-m-d'))
        ->and($dates->dateTimeFormat1)->toBeInstanceOf(\DateTime::class)
        ->and($dates->dateTimeFormat1->format(\DateTimeInterface::RFC3339_EXTENDED))->toBe($date3->format(\DateTimeInterface::RFC3339_EXTENDED));

    $json = $serializer->serialize($dates);

    expect($json)->json()->toBe($data);
});
