<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

interface NormalizerInterface
{
    /**
     * @param array<string, scalar|string[]> $attributes
     *
     * @phpstan-return object|scalar|null|array<mixed>
     * @throws SerializerException
     */
    public function normalize(mixed $data, Normalizer $normalizer, array $attributes): mixed;

    public function supportsNormalization(mixed $data): bool;
}
