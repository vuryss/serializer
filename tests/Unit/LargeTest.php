<?php

declare(strict_types=1);

use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\SerializerInterface;

test('Large data serialization & deserialization', function () {
    $serializer = new Serializer();
    $generator = new \Vuryss\Serializer\Tests\Datasets\Complex1\Generator();
    $object = $generator->generate();

    $serialized = $serializer->serialize($object, SerializerInterface::FORMAT_JSON);
    $deserialized = $serializer->deserialize($serialized, \Vuryss\Serializer\Tests\Datasets\Complex1\RouteData::class, SerializerInterface::FORMAT_JSON);

    expect($deserialized)->toEqual($object);
});
