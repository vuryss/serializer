<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\UnionTypes;

class UnionType2
{
    public function __construct(
        public int $paramB,
    ) {}
}
