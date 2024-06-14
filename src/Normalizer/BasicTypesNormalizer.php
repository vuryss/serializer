<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\Normalizer;
use Vuryss\Serializer\NormalizerInterface;

class BasicTypesNormalizer implements NormalizerInterface
{
    public function normalize(mixed $data, Normalizer $normalizer, array $attributes): mixed
    {
        return $data;
    }

    public function supportsNormalization(mixed $data): bool
    {
        return null === $data || is_scalar($data);
    }
}
