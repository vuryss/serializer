<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Path;
use Vuryss\Serializer\SerializerException;

class ArrayDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, DataType $type, Denormalizer $denormalizer, Path $path): array
    {
        assert(is_array($data));

        $denormalized = [];

        foreach ($data as $key => $value) {
            $path->pushArrayKey($key);

            try {
                $denormalized[$key] = $this->tryToDenormalizeTypesInSequence(
                    $value,
                    $type->listType,
                    $denormalizer,
                    $path
                );
            } finally {
                $path->pop();
            }
        }

        return $denormalized;
    }

    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return is_array($data) && BuiltInType::ARRAY === $type->type;
    }

    /**
     * @param array<DataType> $types
     * @throws SerializerException
     */
    private function tryToDenormalizeTypesInSequence(
        mixed $data,
        array $types,
        Denormalizer $denormalizer,
        Path $path
    ): mixed {
        if ([] === $types) {
            $types = [DataType::fromData($data)];
        }

        foreach ($types as $type) {
            try {
                return $denormalizer->denormalize($data, $type, $path);
            } catch (SerializerException) {
                continue;
            }
        }

        throw new DeserializationImpossibleException(sprintf(
            'Cannot denormalize array element at path "%s" into any of the given types',
            $path->toString()
        ));

        throw new DeserializationImpossibleException('Cannot denormalize data into any of the given types');
    }
}
