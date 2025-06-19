<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\Normalizer;
use Vuryss\Serializer\NormalizerInterface;

class ArrayNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     * @phpstan-param array<mixed> $data
     * @phpstan-return array<mixed>
     */
    public function normalize(mixed $data, Normalizer $normalizer, array $context): array
    {
        return array_map(
            static fn(mixed $item): mixed => $normalizer->normalize($item, $context),
            $data,
        );
    }

    public function supportsNormalization(mixed $data): bool
    {
        return is_array($data);
    }
}
