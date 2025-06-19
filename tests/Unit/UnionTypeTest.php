<?php

use Vuryss\Serializer\SerializerInterface;
use Vuryss\Serializer\Tests\Datasets\UnionTypes\TestClass;

test('Can deserialize into union types - case 1', function () {
    $serializer = new \Vuryss\Serializer\Serializer();
    $data1 = [
        'paramA' => 'string',
    ];
    $data2 = [
        'paramB' => 1,
    ];

    $data = ['param1' => $data1, 'param2' => $data2];
    $object1 = $serializer->deserialize(json_encode($data), TestClass::class, SerializerInterface::FORMAT_JSON);

    expect($object1->param1)->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\UnionTypes\UnionType1::class)
        ->and($object1->param1->paramA)->toBe($data1['paramA'])
        ->and($object1->param2)->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\UnionTypes\UnionType2::class)
        ->and($object1->param2->paramB)->toBe($data2['paramB'])
    ;
});

test('Can deserialize into union types - case 2', function () {
    $serializer = new \Vuryss\Serializer\Serializer();
    $data1 = [
        'paramA' => 'string',
    ];
    $data2 = [
        'paramB' => 1,
    ];

    $data = ['param1' => $data2, 'param2' => $data1];
    $object1 = $serializer->deserialize(json_encode($data), TestClass::class, SerializerInterface::FORMAT_JSON);

    expect($object1->param1)->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\UnionTypes\UnionType2::class)
        ->and($object1->param1->paramB)->toBe($data2['paramB'])
        ->and($object1->param2)->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\UnionTypes\UnionType1::class)
        ->and($object1->param2->paramA)->toBe($data1['paramA'])
    ;
});
