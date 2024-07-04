<?php

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
    $object1 = $serializer->deserialize(json_encode($data), TestClass::class);

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
    $object1 = $serializer->deserialize(json_encode($data), TestClass::class);

    expect($object1->param1)->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\UnionTypes\UnionType2::class)
        ->and($object1->param1->paramB)->toBe($data2['paramB'])
        ->and($object1->param2)->toBeInstanceOf(\Vuryss\Serializer\Tests\Datasets\UnionTypes\UnionType1::class)
        ->and($object1->param2->paramA)->toBe($data1['paramA'])
    ;
});

test('Union type deserialization fails when no types match', function () {
    $serializer = new \Vuryss\Serializer\Serializer();
    $data = [
        'param1' => [
            'paramA' => 'string',
        ],
        'param2' => [
            'paramB' => 1,
        ],
    ];

    $data['param1']['paramA'] = 1;
    $data['param2']['paramB'] = 'string';

    $serializer->deserialize(json_encode($data), TestClass::class);
})->throws(
    \Vuryss\Serializer\Exception\DeserializationImpossibleException::class,
    'Cannot denormalize value "array" at path "$.param1" into any of the given types'
);
