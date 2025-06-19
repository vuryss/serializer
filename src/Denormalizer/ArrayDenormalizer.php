<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\ExceptionInterface;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;

class ArrayDenormalizer implements DenormalizerInterface
{
    /**
     * @inheritDoc
     * @phpstan-return array<mixed>
     */
    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $context = [],
    ): array {
        assert(is_array($data));

        $denormalized = [];

        foreach ($data as $key => $value) {
            $path->pushArrayKey($key);

            try {
                $denormalized[$key] = $this->tryToDenormalizeTypesInSequence(
                    $value,
                    $type->listType,
                    $denormalizer,
                    $path,
                    $context,
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
     * @param array<string, mixed> $context
     *
     * @throws ExceptionInterface
     */
    private function tryToDenormalizeTypesInSequence(
        mixed $data,
        array $types,
        Denormalizer $denormalizer,
        Path $path,
        array $context,
    ): mixed {
        if ([] === $types) {
            $types = [DataType::fromData($data)];
        }

        $lastException = null;

        foreach ($types as $type) {
            try {
                return $denormalizer->denormalize($data, $type, $path, $context);
            } catch (ExceptionInterface $e) {
                $lastException = $e;
                continue;
            }
        }

        throw new DeserializationImpossibleException(sprintf(
            'Cannot denormalize array element at path "%s" into any of the given types',
            $path->toString()
        ), previous: $lastException);
    }
}
