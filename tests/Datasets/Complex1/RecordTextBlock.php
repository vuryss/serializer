<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

class RecordTextBlock
{
    public function __construct(
        public string $identifier,
        public string $content,
    ) {}
}
