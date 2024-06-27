<?php

declare(strict_types=1);

use Vuryss\Serializer\Tests\Datasets\Person;

test('Can ignore properties on serialization', function () {
    $person = new Person();
    $serializer = new \Vuryss\Serializer\Serializer();
    $json = $serializer->serialize($person);

    expect($json)->toBe('{"firstName":"John","lastName":"Doe","age":25,"isStudent":true}');
});

test('Can ignore properties on deserialization', function () {
    $json = '{"firstName":"Jane","lastName":"Bla","age":55,"isStudent":false,"ignoredProperty":"differentValue"}';
    $serializer = new \Vuryss\Serializer\Serializer();
    $person = $serializer->deserialize($json, Person::class);

    expect($person->firstName)->toBe('Jane')
        ->and($person->lastName)->toBe('Bla')
        ->and($person->age)->toBe(55)
        ->and($person->isStudent)->toBe(false)
        ->and($person->ignoredProperty)->toBe('ignoredProperty');
});
