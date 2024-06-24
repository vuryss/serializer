<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\SerializerInterface;

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

    $data = new \Vuryss\Serializer\Tests\Datasets\Group\TopLevelClass();
    $data->nestedPropGroup1 = clone $subLevel1;
    $data->nestedPropGroup2 = clone $subLevel1;
    $data->nestedPropGroup1And2 = clone $subLevel1;
    $data->nestedPropNoGroup = clone $subLevel1;
    $data->propGroup1 = 'topLevelPropGroup1';
    $data->propGroup2 = 'topLevelPropGroup2';
    $data->propGroup1And2 = 'topLevelPropGroup1And2';
    $data->propNoGroup = 'topLevelPropNoGroup';

    $serializer = new Serializer();
    expect($serializer->serialize($data, attributes: [SerializerInterface::ATTRIBUTE_GROUPS => $groups]))
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
