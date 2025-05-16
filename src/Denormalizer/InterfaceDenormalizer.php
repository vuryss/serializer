<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;

class InterfaceDenormalizer implements DenormalizerInterface
{
    /**
     * @inheritDoc
     * @phpstan-param array<mixed> $data
     */
    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $context = [],
    ): mixed {
        foreach ($type->typeMap as $field => $valueToClassName) {
            if (!array_key_exists($field, $data)) {
                continue;
            }

            if (!isset($valueToClassName[$data[$field]])) {
                throw new DeserializationImpossibleException(sprintf(
                    'Cannot denormalize data at path "%s" into interface because none of the mapped types match the value "%s"',
                    $path->toString(),
                    is_string($data[$field]) ? $data[$field] : get_debug_type($data[$field]),
                ));
            }

            $className = $valueToClassName[$data[$field]];
            $dataType = new DataType(BuiltInType::OBJECT, $className);

            return $denormalizer->denormalize($data, $dataType, $path, $context);
        }

        throw new DeserializationImpossibleException(sprintf(
            'Cannot denormalize data at path "%s" into interface because no matching type map was found',
            $path->toString(),
        ));
    }

    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return is_array($data) && BuiltInType::INTERFACE === $type->type;
    }
}
