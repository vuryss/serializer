<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\Context;
use Vuryss\Serializer\Metadata\ReadAccess;
use Vuryss\Serializer\Normalizer;

class ObjectNormalizer implements NormalizerInterface
{
    /**
     * @inheritdoc
     * @phpstan-return array<mixed>
     */
    public function normalize(mixed $data, Normalizer $normalizer, array $context): array
    {
        assert(is_object($data));

        $classMetadata = $normalizer->getMetadataExtractor()->extractClassMetadata($data::class);
        $normalizedData = [];
        /** @var null|string[] $groups */
        $groups = $context[Context::GROUPS] ?? null;

        foreach ($classMetadata->properties as $name => $propertyMetadata) {
            if (
                $propertyMetadata->ignore
                || ReadAccess::NONE === $propertyMetadata->readAccess
                || (null !== $groups && [] === array_intersect($groups, $propertyMetadata->groups))
            ) {
                continue;
            }

            $value = (ReadAccess::DIRECT === $propertyMetadata->readAccess)
                ? $data->{$name}
                : $data->{$propertyMetadata->getterMethod}();

            $localContext = array_merge($context, $propertyMetadata->context);

            if (null === $value) {
                $skipNullValues = $localContext[Context::SKIP_NULL_VALUES] ?? false;

                if (true === $skipNullValues) {
                    continue;
                }
            }

            $normalizedData[$propertyMetadata->serializedName] = $normalizer->normalize($value, $localContext);
        }

        return $normalizedData;
    }

    public function supportsNormalization(mixed $data): bool
    {
        return is_object($data) && \stdClass::class !== $data::class;
    }
}
