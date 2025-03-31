<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\DiscriminatorMap;

readonly class Type1 implements DiscInterface, Disc2Interface
{
    public function __construct(
        public string $key,
        public string $property,
    ) {
    }
}
