<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

interface NormalizerInterface
{
    /**
     * @template T of object|array|scalar|null
     *
     * @return T|T[]
     * @throws SerializerException
     */
    public function normalize(mixed $data, Serializer $serializer): mixed;

    public function supportsNormalization(mixed $data): bool;
}
