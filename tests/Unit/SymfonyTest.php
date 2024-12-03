<?php

declare(strict_types=1);

test('Can serialize with Symfony serializer attributes', function () {
    $object = new \Vuryss\Serializer\Tests\Datasets\Symfony\SymfonyAnnotatedObject();
    $serializer = new \Vuryss\Serializer\Serializer();
    $json = $serializer->serialize($object);

    expect($json)->toBe('{"some_property":5,"another_property":7,"some_field":"foo","another_field":"bar","this_has_priority":"really?","and_another_field":"blah"}');

    $json = $serializer->serialize($object, ['groups' => ['group1']]);

    expect($json)->toBe('{"another_property":7,"some_field":"foo","this_has_priority":"really?","and_another_field":"blah"}');

    $json = $serializer->serialize($object, ['groups' => ['group2']]);

    expect($json)->toBe('{"another_field":"bar"}');
});

test('Can deserialized with Symfony serializer attributes', function () {
    $json = '{"some_property":51,"another_property":71,"some_field":"foobar","another_field":"barfoo","this_has_priority":"nope","and_another_field":"111","ignored":"some-value"}';
    $serializer = new \Vuryss\Serializer\Serializer();
    $person = $serializer->deserialize($json, \Vuryss\Serializer\Tests\Datasets\Symfony\SymfonyAnnotatedObject::class);

    expect($person->someProperty)->toBe(51)
        ->and($person->anotherProperty)->toBe(71)
        ->and($person->someField)->toBe('foobar')
        ->and($person->anotherField)->toBe('barfoo')
        ->and($person->yetAnotherField)->toBe('nope')
        ->and($person->andAnotherField)->toBe('111')
        ->and($person->ignored)->toBe('ignored');

    $person = $serializer->deserialize($json, \Vuryss\Serializer\Tests\Datasets\Symfony\SymfonyAnnotatedObject::class, ['groups' => ['group1']]);

    expect($person->someProperty)->toBe(5)
        ->and($person->anotherProperty)->toBe(71)
        ->and($person->someField)->toBe('foobar')
        ->and($person->anotherField)->toBe('bar')
        ->and($person->yetAnotherField)->toBe('nope')
        ->and($person->andAnotherField)->toBe('111')
        ->and($person->ignored)->toBe('ignored');

    $person = $serializer->deserialize($json, \Vuryss\Serializer\Tests\Datasets\Symfony\SymfonyAnnotatedObject::class, ['groups' => ['group2']]);

    expect($person->someProperty)->toBe(5)
        ->and($person->anotherProperty)->toBe(7)
        ->and($person->someField)->toBe('foo')
        ->and($person->anotherField)->toBe('barfoo')
        ->and($person->yetAnotherField)->toBe('really?')
        ->and($person->andAnotherField)->toBe('blah')
        ->and($person->ignored)->toBe('ignored');
});
