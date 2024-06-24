<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Group;

use Vuryss\Serializer\Attribute\SerializerContext;

class TopLevelClass
{
    #[SerializerContext(groups: ['group1'])]
    public SubLevel1 $nestedPropGroup1;

    #[SerializerContext(groups: ['group2'])]
    public SubLevel1 $nestedPropGroup2;

    #[SerializerContext(groups: ['group1', 'group2'])]
    public SubLevel1 $nestedPropGroup1And2;

    public SubLevel1 $nestedPropNoGroup;

    #[SerializerContext(groups: ['group1'])]
    public string $propGroup1;

    #[SerializerContext(groups: ['group2'])]
    public string $propGroup2;

    #[SerializerContext(groups: ['group1', 'group2'])]
    public string $propGroup1And2;

    public string $propNoGroup;
}
