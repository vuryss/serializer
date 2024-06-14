<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

class Monitor
{
    public function __construct(
        public string $make = 'Dell',
        public bool $is4k = true,
        public int $size = 27,
    ) {
    }
}
