<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Denormalizer\ArrayDenormalizer;
use Vuryss\Serializer\Denormalizer\BasicTypesDenormalizer;
use Vuryss\Serializer\Denormalizer\ObjectDenormalizer;
use Vuryss\Serializer\Exception\DenormalizerNotFoundException;
use Vuryss\Serializer\Metadata\DataType;

/**
 * @internal
 */
class Denormalizer
{
    /**
     * @var DenormalizerInterface[]
     */
    private array $denormalizers;

    /**
     * @param array<DenormalizerInterface> $denormalizers
     */
    public function __construct(
        array $denormalizers = [],
    ) {
        $this->denormalizers = [] === $denormalizers
            ? [
                new BasicTypesDenormalizer(),
                new ArrayDenormalizer(),
                new ObjectDenormalizer(),
            ]
            : $denormalizers;
    }

    /**
     * Denormalized data into the given type.
     *
     * @throws SerializerException
     */
    public function denormalize(mixed $data, DataType $dataType): mixed
    {
        $denormalizer = $this->resolveDenormalizer($data, $dataType);

        /** @psalm-suppress MixedReturnStatement */
        return $denormalizer->denormalize($data, $dataType, $this);
    }

    /**
     * @throws DenormalizerNotFoundException
     */
    private function resolveDenormalizer(mixed $data, DataType $dataType): DenormalizerInterface
    {
        foreach ($this->denormalizers as $denormalizer) {
            if ($denormalizer->supportsDenormalization($data, $dataType)) {
                return $denormalizer;
            }
        }

        throw new DenormalizerNotFoundException(
            sprintf('No denormalizer found for the given data and type: %s, %s', get_debug_type($data), $dataType->type->value),
        );
    }
}
