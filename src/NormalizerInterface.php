<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

interface NormalizerInterface
{
    /**
     * @template T of object|array|scalar|null
     *
     * @param array<string, string|int|float|bool> $attributes
     *
     * @return T|T[]
     * @throws SerializerException
     */
    public function normalize(mixed $data, Normalizer $normalizer, array $attributes): mixed;

    public function supportsNormalization(mixed $data): bool;
}
