<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

readonly class ComponentOption
{
    public function __construct(
        public string $optionName,
        public ?string $originSystemId,
    ) {}
}
