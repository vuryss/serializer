<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Path;

class EnumDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, DataType $type, Denormalizer $denormalizer, Path $path): \BackedEnum
    {
        assert(is_string($data) && null !== $type->className);

        /** @var \BackedEnum $enumClass */
        $enumClass = $type->className;
        $enum = $enumClass::tryFrom($data);

        if (null === $enum) {
            throw new DeserializationImpossibleException(sprintf(
                'Cannot denormalize data "%s" at path "%s" into enum "%s"',
                $data,
                $path->toString(),
                $type->className,
            ));
        }

        return $enum;
    }

    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return is_string($data) && BuiltInType::ENUM === $type->type && null !== $type->className;
    }
}
