<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Metadata\DataType;

interface AdvancedDenormalizerInterface extends DenormalizerInterface
{
    public function supportsDenormalization(mixed $data, DataType $type): bool;
}
