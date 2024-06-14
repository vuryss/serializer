<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Exception\DenormalizerNotFoundException;
use Vuryss\Serializer\Metadata\DataType;

/**
 * @internal
 */
final readonly class Denormalizer
{
    /**
     * @param array<DenormalizerInterface> $denormalizers
     */
    public function __construct(
        private array $denormalizers
    ) {}

    /**
     * Denormalized data into the given type.
     *
     * @throws SerializerException
     */
    public function denormalize(mixed $data, DataType $dataType, Path $path): mixed
    {
        $denormalizer = $this->resolveDenormalizer($data, $dataType, $path);

        /** @psalm-suppress MixedReturnStatement */
        return $denormalizer->denormalize($data, $dataType, $this, $path);
    }

    /**
     * @throws DenormalizerNotFoundException
     */
    private function resolveDenormalizer(mixed $data, DataType $dataType, Path $path): DenormalizerInterface
    {
        foreach ($this->denormalizers as $denormalizer) {
            if ($denormalizer->supportsDenormalization($data, $dataType)) {
                return $denormalizer;
            }
        }

        throw new DenormalizerNotFoundException(sprintf(
            'Could not denormalize data at path %s. Received data of type %s, expected type %s',
            $path->toString(),
            get_debug_type($data),
            $dataType->type->value,
        ));
    }
}
