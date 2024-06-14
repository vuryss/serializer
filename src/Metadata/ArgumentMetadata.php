<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

class ArgumentMetadata
{
    public function __construct(
        public string $name,
        public bool $hasDefaultValue,
        public mixed $defaultValue,
    ) {}
}
