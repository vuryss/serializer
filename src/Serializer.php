<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Denormalizer\ArrayDenormalizer;
use Vuryss\Serializer\Denormalizer\BasicTypesDenormalizer;
use Vuryss\Serializer\Denormalizer\EnumDenormalizer;
use Vuryss\Serializer\Denormalizer\ObjectDenormalizer;
use Vuryss\Serializer\Exception\EncodingException;
use Vuryss\Serializer\Exception\NormalizerNotFoundException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Normalizer\ArrayNormalizer;
use Vuryss\Serializer\Normalizer\BasicTypesNormalizer;
use Vuryss\Serializer\Normalizer\ObjectNormalizer;

readonly class Serializer
{
    /**
     * @var NormalizerInterface[]
     */
    private array $normalizers;

    private Denormalizer $denormalizer;

    /**
     * @param array<NormalizerInterface> $normalizers
     * @param array<DenormalizerInterface> $denormalizers
     */
    public function __construct(
        array $normalizers = [],
        array $denormalizers = [],
    ) {
        $this->normalizers = [] === $normalizers
            ? [
                new BasicTypesNormalizer(),
                new ArrayNormalizer(),
                new ObjectNormalizer(),
            ]
            : $normalizers;

        $denormalizers = [] === $denormalizers
            ? [
                new BasicTypesDenormalizer(),
                new ArrayDenormalizer(),
                new EnumDenormalizer(),
                new ObjectDenormalizer(),
            ]
            : $denormalizers;

        $this->denormalizer = new Denormalizer(denormalizers: $denormalizers);
    }

    /**
     * @throws SerializerException
     */
    public function serialize(mixed $data): string
    {
        $normalizedData = $this->normalize($data);

        try {
            return json_encode($normalizedData, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new EncodingException('Failed to encode data to JSON', previous: $e);
        }
    }

    /**
     * Deserializes data into the given type.
     *
     * @template TObject of object
     * @template TType of null|class-string<TObject>
     *
     * @psalm-param TType $className
     *
     * @psalm-return (TType is class-string<TObject> ? TObject : mixed)
     *
     * @phpstan-return ($className is class-string<TObject> ? TObject : mixed)
     *
     * @throws SerializerException
     */
    public function deserialize(string $data, ?string $className = null): mixed
    {
        try {
            /** @var scalar|array|object|null $decoded */
            $decoded = json_decode($data, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new EncodingException('Failed to decode JSON data', previous: $e);
        }

        return $this->denormalize($decoded, $className);
    }

    /**
     * @throws SerializerException
     */
    public function normalize(mixed $data): mixed
    {
        $normalizer = $this->resolveNormalizer($data);

        return $normalizer->normalize($data, $this);
    }

    /**
     * @throws NormalizerNotFoundException
     */
    private function resolveNormalizer(mixed $data): NormalizerInterface
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supportsNormalization($data)) {
                return $normalizer;
            }
        }

        throw new NormalizerNotFoundException(
            sprintf('No normalizer found for the given data: %s', get_debug_type($data)),
        );
    }

    /**
     * Denormalized data into the given type.
     *
     * @throws SerializerException
     */
    public function denormalize(mixed $data, ?string $type): mixed
    {
        $dataType = null === $type ? DataType::fromData($data) : DataType::fromUserType($type);

        return $this->denormalizer->denormalize($data, $dataType);
    }
}
