<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\Normalizer;
use Vuryss\Serializer\NormalizerInterface;

class EnumNormalizer implements NormalizerInterface
{
    public function normalize(mixed $data, Normalizer $normalizer, array $context): int|string
    {
        assert($data instanceof \BackedEnum);

        return $data->value;
    }

    public function supportsNormalization(mixed $data): bool
    {
        return $data instanceof \BackedEnum;
    }
}
