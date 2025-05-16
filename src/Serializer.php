<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Denormalizer\ArrayDenormalizer;
use Vuryss\Serializer\Denormalizer\BasicTypesDenormalizer;
use Vuryss\Serializer\Denormalizer\DateTimeDenormalizer;
use Vuryss\Serializer\Denormalizer\EnumDenormalizer;
use Vuryss\Serializer\Denormalizer\GenericObjectDenormalizer;
use Vuryss\Serializer\Denormalizer\InterfaceDenormalizer;
use Vuryss\Serializer\Denormalizer\MixedTypeDenormalizer;
use Vuryss\Serializer\Denormalizer\ObjectDenormalizer;
use Vuryss\Serializer\Exception\EncodingException;
use Vuryss\Serializer\Exception\UnsupportedFormatException;
use Vuryss\Serializer\Metadata\CachedMetadataExtractor;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\MetadataExtractor;
use Vuryss\Serializer\Normalizer\ArrayNormalizer;
use Vuryss\Serializer\Normalizer\BasicTypesNormalizer;
use Vuryss\Serializer\Normalizer\DateTimeNormalizer;
use Vuryss\Serializer\Normalizer\EnumNormalizer;
use Vuryss\Serializer\Normalizer\GenericObjectNormalizer;
use Vuryss\Serializer\Normalizer\ObjectNormalizer;

class Serializer implements SerializerInterface
{
    private Normalizer $normalizer;
    private Denormalizer $denormalizer;
    private MetadataExtractorInterface $metadataExtractor;

    /**
     * @param array<NormalizerInterface> $normalizers
     * @param array<DenormalizerInterface> $denormalizers
     * @param array<string, mixed> $context Options normalizers/encoders have access to
     */
    public function __construct(
        array $normalizers = [],
        array $denormalizers = [],
        ?MetadataExtractorInterface $metadataExtractor = null,
        array $context = [],
    ) {
        $this->metadataExtractor = $metadataExtractor ?? new CachedMetadataExtractor(new MetadataExtractor());

        $normalizers = [] === $normalizers
            ? [
                new BasicTypesNormalizer(),
                new ArrayNormalizer(),
                new EnumNormalizer(),
                new DateTimeNormalizer(),
                new ObjectNormalizer(),
                new GenericObjectNormalizer(),
            ]
            : $normalizers;

        $this->normalizer = new Normalizer(
            normalizers: $normalizers,
            metadataExtractor: $this->metadataExtractor,
            context: $context,
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
                new GenericObjectDenormalizer(),
            ]
            : $denormalizers;

        $this->denormalizer = new Denormalizer(
            denormalizers: $denormalizers,
            metadataExtractor: $this->metadataExtractor,
            context: $context,
        );
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        if (SerializerInterface::FORMAT_JSON !== $format) {
            throw new UnsupportedFormatException(sprintf(
                'Unsupported format "%s". Only "%s" is supported.',
                $format,
                SerializerInterface::FORMAT_JSON,
            ));
        }

        $normalizedData = $this->normalize($data, $context);

        try {
            return json_encode($normalizedData, JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
        } catch (\JsonException $e) {
            throw new EncodingException('Failed to encode data to JSON', previous: $e);
        }
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        if (SerializerInterface::FORMAT_JSON !== $format) {
            throw new UnsupportedFormatException(sprintf(
                'Unsupported format "%s". Only "%s" is supported.',
                $format,
                SerializerInterface::FORMAT_JSON,
            ));
        }

        if (!is_string($data)) {
            throw new UnsupportedFormatException(sprintf(
                'Expected string data, got "%s"',
                get_debug_type($data),
            ));
        }

        try {
            $decoded = json_decode($data, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new EncodingException(
                sprintf('Failed to decode JSON data: %s', $e->getMessage()),
                previous: $e,
            );
        }

        return $this->denormalize($decoded, $type, $context);
    }

    /**
     * @param array<string, mixed> $context Options normalizers/encoders have access to
     *
     * @throws ExceptionInterface
     */
    public function normalize(mixed $data, array $context): mixed
    {
        return $this->normalizer->normalize($data, $context);
    }

    /**
     * Denormalized data into the given type.
     *
     * @param array<string, mixed> $context Options normalizers/encoders have access to
     *
     * @throws ExceptionInterface
     */
    public function denormalize(mixed $data, ?string $type, array $context = []): mixed
    {
        $dataType = null === $type ? DataType::fromData($data) : DataType::fromUserType($type);

        return $this->denormalizer->denormalize($data, $dataType, new Path(), $context);
    }
}
