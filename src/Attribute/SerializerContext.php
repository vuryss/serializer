<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class SerializerContext
{
    /**
     * @param array<string, array<string, class-string>>|null $typeMap
     * @param array<string> $groups
     * @param array<string, mixed> $context
     * @param string|null $datetimeTargetTimezone Target timezone for DateTimeInterface objects (e.g., 'America/New_York')
     */
    public function __construct(
        public ?string $name = null,
        public ?array $typeMap = null,
        public bool $ignore = false,
        public array $groups = [],
        public array $context = [],
        public ?string $datetimeTargetTimezone = null,
    ) {}
}
