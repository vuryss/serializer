<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Metadata\DataType;

interface DenormalizerInterface
{
    /**
     * @throws SerializerException
     */
    public function denormalize(mixed $data, DataType $type, Denormalizer $denormalizer, Path $path): mixed;

    public function supportsDenormalization(mixed $data, DataType $type): bool;
}
