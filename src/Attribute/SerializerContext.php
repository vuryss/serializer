<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class SerializerContext
{
    public function __construct(
        public ?string $name = null,
        public ?string $arrayType = null,
    ) {
    }
}
