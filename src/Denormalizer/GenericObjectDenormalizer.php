<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;

readonly class GenericObjectDenormalizer implements DenormalizerInterface
{
    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $context = [],
    ): object {
        return (object) $data;
    }

    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return
            is_array($data)
            && BuiltInType::OBJECT === $type->type
            && null === $type->className;
    }
}
