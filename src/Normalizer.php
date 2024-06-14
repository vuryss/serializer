<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Exception\NormalizerNotFoundException;
use Vuryss\Serializer\Metadata\DataType;

/**
 * @internal
 */
final readonly class Normalizer
{
    /**
     * @param array<NormalizerInterface> $normalizers
     */
    public function __construct(
        private array $normalizers,
    ) {
    }

    /**
     * @param array<string, string|int|float|bool> $attributes
     *
     * @throws SerializerException
     */
    public function normalize(mixed $data, array $attributes): mixed
    {
        $normalizer = $this->resolveNormalizer($data);

        return $normalizer->normalize($data, $this, $attributes);
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
