<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Metadata\DataType;

interface DenormalizerInterface
{
    /**
     * @param array<string, mixed> $context
     *
     * @throws ExceptionInterface
     */
    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $context = [],
    ): mixed;

    public function supportsDenormalization(mixed $data, DataType $type): bool;
}
