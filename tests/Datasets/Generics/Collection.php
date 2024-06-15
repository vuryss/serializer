<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Generics;

/**
 * @template GenericType
 */
readonly class Collection
{
    /**
     * @param array<GenericType> $items
     */
    public function __construct(
        public array $items,
    ) {}
}
