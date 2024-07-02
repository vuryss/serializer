<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\UnionTypes;

class UnionType1
{
    public function __construct(
        public string $paramA,
    ) {}
}
