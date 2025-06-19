<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\ExceptionInterface;
use Vuryss\Serializer\Normalizer;

interface NormalizerInterface
{
    /**
     * @param array<string, mixed> $context
     *
     * @phpstan-return scalar|null|array<mixed>
     * @throws ExceptionInterface
     */
    public function normalize(mixed $data, Normalizer $normalizer, array $context): mixed;

    public function supportsNormalization(mixed $data): bool;
}
