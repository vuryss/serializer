<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;

class InterfaceDenormalizer
{
    /**
     * @throws \Vuryss\Serializer\SerializerException
     */
    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $attributes = [],
    ): mixed {
        if (!is_array($data)) {
            throw new DeserializationImpossibleException(sprintf(
                'Expected type "array" at path "%s", got "%s"',
                $path->toString(),
                gettype($data),
            ));
        }

        foreach ($type->typeMap as $field => $valueToClassName) {
            if (!array_key_exists($field, $data)) {
                continue;
            }

            $className = $valueToClassName[$data[$field]];
            $dataType = new DataType(BuiltInType::OBJECT, $className);

            return $denormalizer->denormalize($data, $dataType, $path, $attributes);
        }

        throw new DeserializationImpossibleException(sprintf(
            'Cannot denormalize data at path "%s" into interface because no matching type map was found',
            $path->toString(),
        ));
    }
}
