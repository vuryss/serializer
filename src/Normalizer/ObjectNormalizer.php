<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\Metadata\ReadAccess;
use Vuryss\Serializer\Normalizer;
use Vuryss\Serializer\NormalizerInterface;

class ObjectNormalizer implements NormalizerInterface
{
    public function normalize(mixed $data, Normalizer $normalizer, array $attributes): array
    {
        assert(is_object($data));

        $classMetadata = $normalizer->getMetadataExtractor()->extractClassMetadata($data::class);
        $normalizedData = [];

        foreach ($classMetadata->properties as $name => $propertyMetadata) {
            if (ReadAccess::NONE === $propertyMetadata->readAccess) {
                continue;
            }

            if (ReadAccess::DIRECT === $propertyMetadata->readAccess) {
                /** @var scalar|null|object|array $value */
                $value = $data->{$name};
            } else {
                /** @var scalar|null|object|array $value */
                $value = $data->{$propertyMetadata->getterMethod}();
            }

            $normalizedData[$propertyMetadata->serializedName] = $normalizer->normalize($value, $propertyMetadata->attributes);
        }

        return $normalizedData;
    }

    public function supportsNormalization(mixed $data): bool
    {
        return is_object($data);
    }
}
