<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\SerializerInterface;
use Vuryss\Serializer\Tests\Datasets\Group\TopLevelClass;
use Vuryss\Serializer\Tests\Datasets\GroupsDatasets;
use Vuryss\Serializer\Context;

test('Serializing only values under specified groups', function ($groups, $expected) {
    $subLevel2 = new \Vuryss\Serializer\Tests\Datasets\Group\SubLevel2();
    $subLevel2->propGroup1 = 'subLevel2PropGroup1';
    $subLevel2->propGroup2 = 'subLevel2PropGroup2';
    $subLevel2->propGroup1And2 = 'subLevel2PropGroup1And2';
    $subLevel2->propNoGroup = 'subLevel2PropNoGroup';

    $subLevel1 = new \Vuryss\Serializer\Tests\Datasets\Group\SubLevel1();
    $subLevel1->nestedPropGroup1 = clone $subLevel2;
    $subLevel1->nestedPropGroup2 = clone $subLevel2;
    $subLevel1->nestedPropGroup1And2 = clone $subLevel2;
    $subLevel1->nestedPropNoGroup = clone $subLevel2;
    $subLevel1->propGroup1 = 'subLevel1PropGroup1';
    $subLevel1->propGroup2 = 'subLevel1PropGroup2';
    $subLevel1->propGroup1And2 = 'subLevel1PropGroup1And2';
    $subLevel1->propNoGroup = 'subLevel1PropNoGroup';

    $data = new TopLevelClass();
    $data->nestedPropGroup1 = clone $subLevel1;
    $data->nestedPropGroup2 = clone $subLevel1;
    $data->nestedPropGroup1And2 = clone $subLevel1;
    $data->nestedPropNoGroup = clone $subLevel1;
    $data->propGroup1 = 'topLevelPropGroup1';
    $data->propGroup2 = 'topLevelPropGroup2';
    $data->propGroup1And2 = 'topLevelPropGroup1And2';
    $data->propNoGroup = 'topLevelPropNoGroup';

    $serializer = new Serializer();
    expect($serializer->serialize($data, SerializerInterface::FORMAT_JSON, context: [Context::GROUPS => $groups]))
        ->toBe(json_encode($expected));
})->with([
    'no groups' => [
        null,
        [
            'nestedPropGroup1' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropNoGroup' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup2' => 'subLevel1PropGroup2',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
                'propNoGroup' => 'subLevel1PropNoGroup',
            ],
            'nestedPropGroup2' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropNoGroup' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup2' => 'subLevel1PropGroup2',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
                'propNoGroup' => 'subLevel1PropNoGroup',
            ],
            'nestedPropGroup1And2' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropNoGroup' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup2' => 'subLevel1PropGroup2',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
                'propNoGroup' => 'subLevel1PropNoGroup',
            ],
            'nestedPropNoGroup' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropNoGroup' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup2' => 'subLevel1PropGroup2',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
                'propNoGroup' => 'subLevel1PropNoGroup',
            ],
            'propGroup1' => 'topLevelPropGroup1',
            'propGroup2' => 'topLevelPropGroup2',
            'propGroup1And2' => 'topLevelPropGroup1And2',
            'propNoGroup' => 'topLevelPropNoGroup',
        ],
    ],
    'group1' => [
        ['group1'],
        [
            'nestedPropGroup1' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
            ],
            'nestedPropGroup1And2' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
            ],
            'propGroup1' => 'topLevelPropGroup1',
            'propGroup1And2' => 'topLevelPropGroup1And2',
        ],
    ],
    'group2' => [
        ['group2'],
        [
            'nestedPropGroup2' => [
                'nestedPropGroup2' => [
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'propGroup2' => 'subLevel1PropGroup2',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
            ],
            'nestedPropGroup1And2' => [
                'nestedPropGroup2' => [
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'propGroup2' => 'subLevel1PropGroup2',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
            ],
            'propGroup2' => 'topLevelPropGroup2',
            'propGroup1And2' => 'topLevelPropGroup1And2',
        ],
    ],
    'group1 and group2' => [
        ['group1', 'group2'],
        [
            'nestedPropGroup1' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'nestedPropGroup2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup2' => 'subLevel1PropGroup2',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
            ],
            'nestedPropGroup2' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'nestedPropGroup2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup2' => 'subLevel1PropGroup2',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
            ],
            'nestedPropGroup1And2' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'nestedPropGroup2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup2' => 'subLevel2PropGroup2',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup2' => 'subLevel1PropGroup2',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
            ],
            'propGroup1' => 'topLevelPropGroup1',
            'propGroup2' => 'topLevelPropGroup2',
            'propGroup1And2' => 'topLevelPropGroup1And2',
        ],
    ],
    'group1 and default combination' => [
        ['group1', 'default'],
        [
            'nestedPropGroup1' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropNoGroup' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
                'propNoGroup' => 'subLevel1PropNoGroup',
            ],
            'nestedPropGroup1And2' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropNoGroup' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
                'propNoGroup' => 'subLevel1PropNoGroup',
            ],
            'nestedPropNoGroup' => [
                'nestedPropGroup1' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropGroup1And2' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'nestedPropNoGroup' => [
                    'propGroup1' => 'subLevel2PropGroup1',
                    'propGroup1And2' => 'subLevel2PropGroup1And2',
                    'propNoGroup' => 'subLevel2PropNoGroup',
                ],
                'propGroup1' => 'subLevel1PropGroup1',
                'propGroup1And2' => 'subLevel1PropGroup1And2',
                'propNoGroup' => 'subLevel1PropNoGroup',
            ],
            'propGroup1' => 'topLevelPropGroup1',
            'propGroup1And2' => 'topLevelPropGroup1And2',
            'propNoGroup' => 'topLevelPropNoGroup',
        ],
    ],
]);

test('Deserializing values from all groups', function () {
    $fullData = GroupsDatasets::deserializationDataSet();
    $serializer = new Serializer();
    $object = $serializer->deserialize(
        json_encode($fullData),
        TopLevelClass::class,
        SerializerInterface::FORMAT_JSON,
        [Context::GROUPS => null],
    );

    expect(isset($object->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup2->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropNoGroup))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropNoGroup->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->propNoGroup))->toBeTrue()

        ->and(isset($object->propGroup1))->toBeTrue()
        ->and(isset($object->propGroup2))->toBeTrue()
        ->and(isset($object->propGroup1And2))->toBeTrue()
        ->and(isset($object->propNoGroup))->toBeTrue();
});

test('Deserializing values only in group1', function () {
    $fullData = GroupsDatasets::deserializationDataSet();
    $serializer = new Serializer();
    $object = $serializer->deserialize(
        json_encode($fullData),
        TopLevelClass::class,
        SerializerInterface::FORMAT_JSON,
        [Context::GROUPS => ['group1']],
    );

    expect(isset($object->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->propGroup1))->toBeTrue()
        ->and(isset($object->propGroup2))->toBeFalse()
        ->and(isset($object->propGroup1And2))->toBeTrue()
        ->and(isset($object->propNoGroup))->toBeFalse()
    ;
});

test('Deserializing values only group2', function () {
    $fullData = GroupsDatasets::deserializationDataSet();
    $serializer = new Serializer();
    $object = $serializer->deserialize(
        json_encode($fullData),
        TopLevelClass::class,
        SerializerInterface::FORMAT_JSON,
        [Context::GROUPS => ['group2']],
    );

    expect(isset($object->nestedPropGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->propGroup1))->toBeFalse()
        ->and(isset($object->propGroup2))->toBeTrue()
        ->and(isset($object->propGroup1And2))->toBeTrue()
        ->and(isset($object->propNoGroup))->toBeFalse()
    ;
});

test('Deserializing values from group 1 and group 2', function () {
    $fullData = GroupsDatasets::deserializationDataSet();
    $serializer = new Serializer();
    $object = $serializer->deserialize(
        json_encode($fullData),
        TopLevelClass::class,
        SerializerInterface::FORMAT_JSON,
        [Context::GROUPS => ['group1', 'group2']],
    );

    expect(isset($object->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propGroup2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->propGroup1))->toBeTrue()
        ->and(isset($object->propGroup2))->toBeTrue()
        ->and(isset($object->propGroup1And2))->toBeTrue()
        ->and(isset($object->propNoGroup))->toBeFalse()
    ;
});

test('Deserializing values from group 1 and default group combination', function () {
    $fullData = GroupsDatasets::deserializationDataSet();
    $serializer = new Serializer();
    $object = $serializer->deserialize(
        json_encode($fullData),
        TopLevelClass::class,
        SerializerInterface::FORMAT_JSON,
        [Context::GROUPS => ['group1', 'default']],
    );

    expect(isset($object->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropGroup1And2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->nestedPropNoGroup->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropGroup1And2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->nestedPropNoGroup->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropGroup1And2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->nestedPropNoGroup->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropGroup1And2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropNoGroup))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup1))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propGroup1And2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup2->propNoGroup))->toBeFalse()

        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropGroup1And2->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->nestedPropNoGroup->propNoGroup))->toBeTrue()

        ->and(isset($object->nestedPropNoGroup->propGroup1))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->propGroup2))->toBeFalse()
        ->and(isset($object->nestedPropNoGroup->propGroup1And2))->toBeTrue()
        ->and(isset($object->nestedPropNoGroup->propNoGroup))->toBeTrue()

        ->and(isset($object->propGroup1))->toBeTrue()
        ->and(isset($object->propGroup2))->toBeFalse()
        ->and(isset($object->propGroup1And2))->toBeTrue()
        ->and(isset($object->propNoGroup))->toBeTrue();
});
