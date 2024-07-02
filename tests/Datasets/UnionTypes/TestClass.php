<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\UnionTypes;

class TestClass
{
    public function __construct(
        public UnionType1|UnionType2 $param1,
        public UnionType2|UnionType1 $param2,
    ) {}
}
