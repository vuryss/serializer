<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

class SampleWithArray
{
    /**
     * @param array<string> $data
     */
    public function __construct(
        public array $data,
    ) {}
}
