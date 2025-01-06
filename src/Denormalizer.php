<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Exception\DenormalizerNotFoundException;
use Vuryss\Serializer\Metadata\DataType;

final readonly class Denormalizer
{
    /**
     * @param array<DenormalizerInterface> $denormalizers
     * @param array<string, scalar|string[]> $attributes
     */
    public function __construct(
        private array $denormalizers,
        private MetadataExtractorInterface $metadataExtractor,
        private array $attributes = [],
    ) {}

    /**
     * Denormalized data into the given type.
     *
     * @param array<string, scalar|string[]> $attributes
     *
     * @throws SerializerException
     */
    public function denormalize(mixed $data, DataType $dataType, Path $path, array $attributes): mixed
    {
        $dataType->attributes += $this->attributes;
        $denormalizer = $this->resolveDenormalizer($data, $dataType, $path);

        return $denormalizer->denormalize($data, $dataType, $this, $path, $attributes);
    }

    public function getMetadataExtractor(): MetadataExtractorInterface
    {
        return $this->metadataExtractor;
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
