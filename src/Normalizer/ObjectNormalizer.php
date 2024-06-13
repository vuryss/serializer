<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\Metadata\MetadataExtractor;
use Vuryss\Serializer\Metadata\ReadAccess;
use Vuryss\Serializer\NormalizerInterface;
use Vuryss\Serializer\Serializer;

class ObjectNormalizer implements NormalizerInterface
{
    public function normalize(mixed $data, Serializer $serializer): array
    {
        assert(is_object($data));

        $metadataExtractor = new MetadataExtractor();
        $classMetadata = $metadataExtractor->extractClassMetadata($data::class);
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

            $normalizedData[$propertyMetadata->serializedName] = $serializer->normalize($value);
        }

        return $normalizedData;
    }

    public function supportsNormalization(mixed $data): bool
    {
        return is_object($data);
    }
}
