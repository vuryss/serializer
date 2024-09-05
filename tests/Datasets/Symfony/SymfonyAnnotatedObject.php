<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Symfony;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Vuryss\Serializer\Attribute\SerializerContext;

class SymfonyAnnotatedObject
{
    #[SerializerContext(name: 'some_property')]
    public int $someProperty = 5;

    #[Groups(['group1'])]
    #[SerializedName('another_property')]
    public int $anotherProperty = 7;

    public function __construct(
        #[Groups(['group1'])]
        #[SerializedName('some_field')]
        public string $someField = 'foo',

        #[Groups(['group2'])]
        #[SerializedName('another_field')]
        public string $anotherField = 'bar',

        #[Groups(['group1'])]
        #[SerializedName('yet_another_field')]
        #[SerializerContext(name: 'this_has_priority')]
        public string $yetAnotherField = 'really?',

        #[Groups(['group2'])]
        #[SerializedName('and_another_field')]
        #[SerializerContext(groups: ['group1'])]
        public string $andAnotherField = 'blah',
    ) {}
}
