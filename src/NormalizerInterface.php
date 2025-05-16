<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

interface NormalizerInterface
{
    /**
     * @param array<string, mixed> $context
     *
     * @phpstan-return object|scalar|null|array<mixed>
     * @throws ExceptionInterface
     */
    public function normalize(mixed $data, Normalizer $normalizer, array $context): mixed;

    public function supportsNormalization(mixed $data): bool;
}
