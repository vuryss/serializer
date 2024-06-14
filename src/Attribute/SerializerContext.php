<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class SerializerContext
{
    /**
     * @param array<string, array<string, class-string>> $typeMap
     * @param array<string, string|int|float|bool> $attributes
     */
    public function __construct(
        public ?string $name = null,
        public ?array $typeMap = null,
        public array $attributes = [],
    ) {}
}
