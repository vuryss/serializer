<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

class GroupsDatasets
{
    public static function deserializationDataSet(): array
    {
        return [
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
        ];
    }
}
