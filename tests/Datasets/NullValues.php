<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

class NullValues
{
    public ?string $nullableString = null;

    public function __construct(
        public ?int $nullableInt = null,
    ) {}
}
