<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Denormalizer\DateTimeDenormalizer;
use Vuryss\Serializer\Exception\EncodingException;
use Vuryss\Serializer\Metadata\CachedMetadataExtractor;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\MetadataExtractor;
use Vuryss\Serializer\Normalizer\DateTimeNormalizer;

class Serializer implements SerializerInterface
{
    private Normalizer $normalizer;
    private Denormalizer $denormalizer;
    private MetadataExtractorInterface $metadataExtractor;

    /**
     * @param array<NormalizerInterface> $normalizers
     * @param array<DenormalizerInterface> $denormalizers
     * @param array<string, scalar|string[]> $attributes
     */
    public function __construct(
        array $normalizers = [],
        array $denormalizers = [],
        ?MetadataExtractorInterface $metadataExtractor = null,
        array $attributes = [],
    ) {
        $this->metadataExtractor = $metadataExtractor ?? new CachedMetadataExtractor(new MetadataExtractor());

        $this->normalizer = new Normalizer(
            normalizers: [] === $normalizers ? [new DateTimeNormalizer()] : $normalizers,
            metadataExtractor: $this->metadataExtractor,
            attributes: $attributes,
        );

        $this->denormalizer = new Denormalizer(
            denormalizers: [] === $denormalizers ? [new DateTimeDenormalizer()] : $denormalizers,
            metadataExtractor: $this->metadataExtractor,
            attributes: $attributes,
        );
    }

    public function serialize(mixed $data, array $attributes = []): string
    {
        $normalizedData = $this->normalize($data, $attributes);

        try {
            return json_encode($normalizedData, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
        } catch (\JsonException $e) {
            throw new EncodingException('Failed to encode data to JSON', previous: $e);
        }
    }

    public function deserialize(string $data, ?string $type = null, array $attributes = []): mixed
    {
        try {
            /** @var scalar|array|object|null $decoded */
            $decoded = json_decode($data, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new EncodingException('Failed to decode JSON data', previous: $e);
        }

        return $this->denormalize($decoded, $type, $attributes);
    }

    /**
     * @param array<string, scalar|string[]> $attributes
     *
     * @throws SerializerException
     */
    public function normalize(mixed $data, array $attributes): mixed
    {
        return $this->normalizer->normalize($data, $attributes);
    }

    /**
     * Denormalized data into the given type.
     *
     * @param array<string, scalar|string[]> $attributes
     *
     * @throws SerializerException
     */
    public function denormalize(mixed $data, ?string $type, array $attributes = []): mixed
    {
        $dataType = null === $type ? DataType::fromData($data) : DataType::fromUserType($type);

        return $this->denormalizer->denormalize($data, $dataType, new Path(), $attributes);
    }
}
