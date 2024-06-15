<?php

declare(strict_types=1);

test('Generics serialization', function () {
    $collection = new \Vuryss\Serializer\Tests\Datasets\Generics\Collection([
        new \Vuryss\Serializer\Tests\Datasets\Generics\Item('item1'),
        new \Vuryss\Serializer\Tests\Datasets\Generics\Item('item2'),
    ]);

    $serializer = new \Vuryss\Serializer\Serializer();
    $serialized = $serializer->serialize($collection);

    expect($serialized)->toBe('{"items":[{"name":"item1"},{"name":"item2"}]}');
});
