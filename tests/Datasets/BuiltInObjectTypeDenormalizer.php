<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\AdvancedDenormalizerInterface;
use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;

class BuiltInObjectTypeDenormalizer implements AdvancedDenormalizerInterface
{
    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return isset($data['#type']);
    }

    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $attributes = [],
    ): mixed {
        assert(is_array($data));

        $objectDenormalizer = new Denormalizer\ObjectDenormalizer();
        $dataType = new DataType(BuiltInType::OBJECT, $data['#type']);
        unset($data['#type']);

        return $objectDenormalizer->denormalize($data, $dataType, $denormalizer, $path, $attributes);
    }

    public function getSupportedClassNames(): array
    {
        return [];
    }
}
