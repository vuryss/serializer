<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\Normalizer;
use Vuryss\Serializer\NormalizerInterface;

class BasicTypesNormalizer implements NormalizerInterface
{
    /**
     * @inheritDoc
     * @phpstan-param scalar|null $data
     */
    public function normalize(mixed $data, Normalizer $normalizer, array $context): mixed
    {
        return $data;
    }

    public function supportsNormalization(mixed $data): bool
    {
        return null === $data || is_scalar($data);
    }
}
