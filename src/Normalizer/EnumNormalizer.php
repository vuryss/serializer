<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\NormalizerInterface;
use Vuryss\Serializer\Serializer;

class EnumNormalizer implements NormalizerInterface
{
    public function normalize(mixed $data, Serializer $serializer): int|string
    {
        assert($data instanceof \BackedEnum);

        return $data->value;
    }

    public function supportsNormalization(mixed $data): bool
    {
        return $data instanceof \BackedEnum;
    }
}
