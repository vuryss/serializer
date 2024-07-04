<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;

class EnumDenormalizer
{
    /**
     * @throws DeserializationImpossibleException
     */
    public function denormalize(
        mixed $data,
        DataType $type,
        Path $path,
    ): \BackedEnum {
        assert(null !== $type->className);

        if (!is_string($data)) {
            throw new DeserializationImpossibleException(sprintf(
                'Expected type "string" at path "%s", got "%s"',
                $path->toString(),
                gettype($data),
            ));
        }

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
}
