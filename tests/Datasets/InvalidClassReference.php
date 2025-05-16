<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

readonly class InvalidClassReference
{
    public function __construct(
        public InvalidClassName $invalidClassName,
    ) {}
}
