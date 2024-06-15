<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Generics;

class Item
{
    public function __construct(
        public string $name,
    ) {}
}
