<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Exception\DenormalizerNotFoundException;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\SerializerException;

class ArrayDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, DataType $type, Denormalizer $denormalizer): array
    {
        assert(is_array($data));

        return array_map(
            fn (mixed $item): mixed => $this->tryToDenormalizeTypesInSequence($item, $type->listType, $denormalizer),
            $data
        );
    }

    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return is_array($data) && BuiltInType::ARRAY === $type->type;
    }

    /**
     * @param array<DataType> $types
     * @throws SerializerException
     */
    private function tryToDenormalizeTypesInSequence(mixed $data, array $types, Denormalizer $denormalizer): mixed
    {
        if ([] === $types) {
            $types = [DataType::fromData($data)];
        }

        foreach ($types as $type) {
            try {
                return $denormalizer->denormalize($data, $type);
            } catch (SerializerException) {
                continue;
            }
        }

        throw new DeserializationImpossibleException('Cannot denormalize data into any of the given types');
    }
}
