<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

interface AdvancedNormalizerInterface extends NormalizerInterface
{
    public function supportsNormalization(mixed $data): bool;
}
