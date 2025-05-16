<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

class ComponentData
{
    /**
     * @param ComponentOption[]|string[] $componentOptions
     */
    public function __construct(
        public readonly string $refType,
        public ?string $targetValue,
        public readonly array $componentOptions,
    ) {
        $this->targetValue = null !== $this->targetValue ? trim($this->targetValue) : null;
    }
}
