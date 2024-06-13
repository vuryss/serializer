<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\BuiltInType;

class EnumDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, DataType $type, Denormalizer $denormalizer): \BackedEnum
    {
        assert(is_string($data) && null !== $type->className);

        /** @var \BackedEnum $enumClass */
        $enumClass = $type->className;
        $enum = $enumClass::tryFrom($data);

        if (null === $enum) {
            throw new DeserializationImpossibleException(
                sprintf('Cannot denormalize data "%s" into enum "%s"', $data, $type->className)
            );
        }

        return $enum;
    }

    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return is_string($data) && BuiltInType::ENUM === $type->type && null !== $type->className;
    }
}
