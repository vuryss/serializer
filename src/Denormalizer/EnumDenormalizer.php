<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;

class EnumDenormalizer implements DenormalizerInterface
{
    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $context = [],
    ): \BackedEnum {
        assert((is_string($data) || is_int($data)) && null !== $type->className);

        if (!is_subclass_of($type->className, \BackedEnum::class)) {
            throw new DeserializationImpossibleException(sprintf(
                'Class "%s" is not a backed enum. Cannot denormalize into enum that has no backing type',
                $type->className,
            ));
        }

        $enumClass = $type->className;

        try {
            $enum = $enumClass::tryFrom($data);
        } catch (\TypeError) {
            // This happens when the enum's backing type doesn't match the data type
            // (e.g., passing string to int-backed enum or int to string-backed enum)
            throw new DeserializationImpossibleException(sprintf(
                'Cannot denormalize data "%s" at path "%s" into enum "%s"',
                $data,
                $path->toString(),
                $type->className,
            ));
        }

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
        return (is_string($data) || is_int($data)) && BuiltInType::ENUM === $type->type && null !== $type->className;
    }
}
