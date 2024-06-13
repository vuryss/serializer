<?php


use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\Tests\Datasets\ClassWithNestedClass;
use Vuryss\Serializer\Tests\Datasets\Person;
use Vuryss\Serializer\Tests\Datasets\SerializedName;

test('Serializing data structures', function ($data, $expected) {
    $serializer = new Serializer();
    expect($serializer->serialize($data))->toBe($expected);
})->with(
    [
        [null, 'null'],
        [true, 'true'],
        [false, 'false'],
        [1, '1'],
        [1.1, '1.1'],
        ['string', '"string"'],
        [['list', 'of', 'data', false, true, null, 1, 1.2], '["list","of","data",false,true,null,1,1.2]'],
        [['key' => 'value'], '{"key":"value"}'],
        [['key' => ['nested' => 'value']], '{"key":{"nested":"value"}}'],
        [['key' => ['nested' => ['deeply' => 'nested']]], '{"key":{"nested":{"deeply":"nested"}}}'],
        [new Person(), '{"firstName":"John","lastName":"Doe","age":25,"isStudent":true}'],
        [new SerializedName(), '{"changedPropertyName":"value"}'],
        [new ClassWithNestedClass(), '{"person":{"firstName":"John","lastName":"Doe","age":25,"isStudent":true},"nonNestedProperty":"nonNestedProperty"}'],
    ]
);
