<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Exception\NormalizerNotFoundException;

final readonly class Normalizer
{
    /**
     * @param array<NormalizerInterface> $normalizers
     * @param array<string, scalar|string[]> $attributes
     */
    public function __construct(
        private array $normalizers,
        private MetadataExtractorInterface $metadataExtractor,
        private array $attributes = [],
    ) {}

    /**
     * @param array<string, scalar|string[]> $attributes
     *
     * @throws SerializerException
     */
    public function normalize(mixed $data, array $attributes): mixed
    {
        $normalizer = $this->resolveNormalizer($data);

        $attributes = $attributes + $this->attributes;

        return $normalizer->normalize($data, $this, $attributes);
    }

    public function getMetadataExtractor(): MetadataExtractorInterface
    {
        return $this->metadataExtractor;
    }

    /**
     * @throws NormalizerNotFoundException
     */
    private function resolveNormalizer(mixed $data): NormalizerInterface
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supportsNormalization($data)) {
                return $normalizer;
            }
        }

        throw new NormalizerNotFoundException(
            sprintf('No normalizer found for the given data: %s', get_debug_type($data)),
        );
    }
}
