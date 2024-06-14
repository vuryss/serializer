<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

class ConstructorMetadata
{
    /**
     * @param bool $isPublic
     * @param ArgumentMetadata[] $arguments
     */
    public function __construct(
        public bool $isPublic,
        public array $arguments,
    ) {}
}
