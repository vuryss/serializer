<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\AdvancedNormalizerInterface;
use Vuryss\Serializer\Normalizer;

class BuiltInObjectTypeNormalizer implements AdvancedNormalizerInterface
{
    public function normalize(mixed $data, Normalizer $normalizer, array $attributes): array
    {
        assert(is_object($data));
        $objectNormalizer = new Normalizer\ObjectNormalizer();
        $normalizedObject = $objectNormalizer->normalize($data, $normalizer, $attributes);

        /** @psalm-suppress InvalidReturnStatement */
        return ['#type' => $data::class] + $normalizedObject;
    }

    public function supportsNormalization(mixed $data): bool
    {
        return is_object($data);
    }
    public function getSupportedClassNames(): array
    {
        return [];
    }
}
