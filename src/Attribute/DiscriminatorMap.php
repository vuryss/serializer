<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Attribute;

use Vuryss\Serializer\Exception\InvalidAttributeUsageException;

#[\Attribute(\Attribute::TARGET_CLASS)]
readonly class DiscriminatorMap
{
    /**
     * @param array<string, class-string> $mapping
     *
     * @throws InvalidAttributeUsageException
     */
    public function __construct(
        public string $typeProperty,
        public array $mapping,
    ) {
        if ('' === $typeProperty) {
            throw new InvalidAttributeUsageException('Type property cannot be an empty string.');
        }

        if ([] === $mapping) {
            throw new InvalidAttributeUsageException('Mapping array cannot be empty.');
        }
    }
}
