<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\Metadata\ReadAccess;
use Vuryss\Serializer\Normalizer;
use Vuryss\Serializer\NormalizerInterface;
use Vuryss\Serializer\SerializerInterface;

class ObjectNormalizer implements NormalizerInterface
{
    public function normalize(mixed $data, Normalizer $normalizer, array $attributes): array
    {
        assert(is_object($data));

        $classMetadata = $normalizer->getMetadataExtractor()->extractClassMetadata($data::class);
        $normalizedData = [];
        /** @var null|string[] $groups */
        $groups = $attributes[SerializerInterface::ATTRIBUTE_GROUPS] ?? null;

        foreach ($classMetadata->properties as $name => $propertyMetadata) {
            if (ReadAccess::NONE === $propertyMetadata->readAccess) {
                continue;
            }

            if (null !== $groups && [] === array_intersect($groups, $propertyMetadata->groups)) {
                continue;
            }

            if (ReadAccess::DIRECT === $propertyMetadata->readAccess) {
                /** @var scalar|null|object|array $value */
                $value = $data->{$name};
            } else {
                /** @var scalar|null|object|array $value */
                $value = $data->{$propertyMetadata->getterMethod}();
            }

            $localAttributes = array_merge($attributes, $propertyMetadata->attributes);

            if (null === $value) {
                $skipNullValues = $localAttributes[SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES] ?? false;

                if (true === $skipNullValues) {
                    continue;
                }
            }

            $normalizedData[$propertyMetadata->serializedName] = $normalizer->normalize($value, $localAttributes);
        }

        return $normalizedData;
    }

    public function supportsNormalization(mixed $data): bool
    {
        return is_object($data);
    }
}
