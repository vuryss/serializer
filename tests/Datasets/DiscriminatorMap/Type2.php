<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\DiscriminatorMap;

readonly class Type2 implements DiscInterface, Disc2Interface
{
    public function __construct(
        public string $key,
        public int $property,
    ) {
    }
}
