<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

class ClassMetadata
{
    /**
     * @param array<string, PropertyMetadata> $properties
     */
    public function __construct(
        public array $properties,
        public ConstructorMetadata $constructor,
    ) {}
}
