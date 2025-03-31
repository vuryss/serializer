<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\DiscriminatorMap;

readonly class WrapperClass
{
    public function __construct(
        public DiscInterface $disc,
        public Disc2Interface $disc2,
    ) {
    }
}
