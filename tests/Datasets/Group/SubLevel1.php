<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Group;

use Vuryss\Serializer\Attribute\SerializerContext;

class SubLevel1
{
    #[SerializerContext(groups: ['group1'])]
    public SubLevel2 $nestedPropGroup1;

    #[SerializerContext(groups: ['group2'])]
    public SubLevel2 $nestedPropGroup2;

    #[SerializerContext(groups: ['group1', 'group2'])]
    public SubLevel2 $nestedPropGroup1And2;

    public SubLevel2 $nestedPropNoGroup;

    #[SerializerContext(groups: ['group1'])]
    public string $propGroup1;

    #[SerializerContext(groups: ['group2'])]
    public string $propGroup2;

    #[SerializerContext(groups: ['group1', 'group2'])]
    public string $propGroup1And2;

    public string $propNoGroup;
}
