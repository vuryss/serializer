<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\ExceptionInterface;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;

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
