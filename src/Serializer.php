<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Denormalizer\ArrayDenormalizer;
use Vuryss\Serializer\Denormalizer\BasicTypesDenormalizer;
use Vuryss\Serializer\Denormalizer\DateTimeDenormalizer;
use Vuryss\Serializer\Denormalizer\EnumDenormalizer;
use Vuryss\Serializer\Denormalizer\InterfaceDenormalizer;
use Vuryss\Serializer\Denormalizer\MixedTypeDenormalizer;
use Vuryss\Serializer\Denormalizer\ObjectDenormalizer;
use Vuryss\Serializer\Exception\EncodingException;
use Vuryss\Serializer\Metadata\CachedMetadataExtractor;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\MetadataExtractor;
use Vuryss\Serializer\Normalizer\ArrayNormalizer;
use Vuryss\Serializer\Normalizer\BasicTypesNormalizer;
use Vuryss\Serializer\Normalizer\DateTimeNormalizer;
use Vuryss\Serializer\Normalizer\EnumNormalizer;
use Vuryss\Serializer\Normalizer\ObjectNormalizer;

class Serializer implements SerializerInterface
{
    private Normalizer $normalizer;
    private Denormalizer $denormalizer;
    private MetadataExtractorInterface $metadataExtractor;

    /**
     * @param array<NormalizerInterface> $normalizers
     * @param array<DenormalizerInterface> $denormalizers
     * @param array<string, string|int|float|bool> $attributes
     */
    public function __construct(
        array $normalizers = [],
        array $denormalizers = [],
        ?MetadataExtractorInterface $metadataExtractor = null,
        array $attributes = [],
    ) {
        $this->metadataExtractor = $metadataExtractor ?? new CachedMetadataExtractor(new MetadataExtractor());

        $normalizers = [] === $normalizers
            ? [
                new BasicTypesNormalizer(),
                new ArrayNormalizer(),
                new EnumNormalizer(),
                new DateTimeNormalizer(),
                new ObjectNormalizer(),
            ]
            : $normalizers;

        $this->normalizer = new Normalizer(
            normalizers: $normalizers,
            metadataExtractor: $this->metadataExtractor,
            attributes: $attributes,
        );

        $denormalizers = [] === $denormalizers
            ? [
                new BasicTypesDenormalizer(),
                new ArrayDenormalizer(),
                new EnumDenormalizer(),
                new DateTimeDenormalizer(),
                new ObjectDenormalizer(),
                new InterfaceDenormalizer(),
                new MixedTypeDenormalizer(),
            ]
            : $denormalizers;

        $this->denormalizer = new Denormalizer(
            denormalizers: $denormalizers,
            metadataExtractor: $this->metadataExtractor,
            attributes: $attributes,
        );
    }

    public function serialize(mixed $data): string
    {
        $normalizedData = $this->normalize($data);

        try {
            return json_encode($normalizedData, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
        } catch (\JsonException $e) {
            throw new EncodingException('Failed to encode data to JSON', previous: $e);
        }
    }

    public function deserialize(string $data, ?string $type = null): mixed
    {
        try {
            /** @var scalar|array|object|null $decoded */
            $decoded = json_decode($data, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new EncodingException('Failed to decode JSON data', previous: $e);
        }

        return $this->denormalize($decoded, $type);
    }

    /**
     * @throws SerializerException
     */
    public function normalize(mixed $data): mixed
    {
        return $this->normalizer->normalize($data, []);
    }

    /**
     * Denormalized data into the given type.
     *
     * @throws SerializerException
     */
    public function denormalize(mixed $data, ?string $type): mixed
    {
        $dataType = null === $type ? DataType::fromData($data) : DataType::fromUserType($type);

        return $this->denormalizer->denormalize($data, $dataType, new Path());
    }
}
