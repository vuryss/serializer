<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\Normalizer;
use Vuryss\Serializer\NormalizerInterface;

class GenericObjectNormalizer implements NormalizerInterface
{
    /**
     * @param object $data
     * @return array<mixed>
     */
    public function normalize(mixed $data, Normalizer $normalizer, array $context): array
    {
        return (array) $data;
    }

    public function supportsNormalization(mixed $data): bool
    {
        return is_object($data) && \stdClass::class === $data::class;
    }
}
