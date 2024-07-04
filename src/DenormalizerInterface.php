<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Metadata\DataType;

interface DenormalizerInterface
{
    /**
     * @param array<string, scalar|string[]> $attributes
     *
     * @throws SerializerException
     */
    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $attributes = [],
    ): mixed;

    public function getSupportedClassNames(): array;
}
