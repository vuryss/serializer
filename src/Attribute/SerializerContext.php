<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class SerializerContext
{
    /**
     * @param array<string, array<string, class-string>>|null $typeMap
     * @param array<string> $groups
     * @param array<string, string|int|float|bool> $context
     */
    public function __construct(
        public ?string $name = null,
        public ?array $typeMap = null,
        public bool $ignore = false,
        public array $groups = [],
        public array $context = [],
    ) {}
}
