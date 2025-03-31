<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

readonly class DiscriminatorMapMetadata
{
    /**
     * @param string $field
     * @param array<string, class-string> $map
     */
    public function __construct(
        public string $field,
        public array $map,
    ) {
    }
}
