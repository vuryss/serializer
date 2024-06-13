<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\NormalizerInterface;
use Vuryss\Serializer\Serializer;
use Vuryss\Serializer\SerializerException;

class ArrayNormalizer implements NormalizerInterface
{
    /**
     * @throws SerializerException
     */
    public function normalize(mixed $data, Serializer $serializer): array
    {
        return array_map(fn (mixed $item): mixed => $serializer->normalize($item), $data);
    }

    public function supportsNormalization(mixed $data): bool
    {
        return is_array($data);
    }
}
