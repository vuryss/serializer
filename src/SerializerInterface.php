<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

/**
 * Fully compatible with Symfony SerializerInterface to allow interoperability between the two.
 *
 * @phpstan-import-type ContextOptions from Context
 */
interface SerializerInterface
{
    public const string FORMAT_JSON = 'json';

    /**
     * Serializes data in the appropriate format.
     *
     * @phpstan-param mixed          $data
     * @phpstan-param 'json'         $format
     * @phpstan-param ContextOptions $context Options normalizers have access to
     *
     * @return string
     * @throws ExceptionInterface
     */
    public function serialize(mixed $data, string $format, array $context = []): string;

    /**
     * Deserializes data into the given type.
     *
     * @template TObject of object
     * @template TType of string|class-string<TObject>
     *
     * @phpstan-param mixed          $data
     * @phpstan-param TType          $type
     * @phpstan-param 'json'         $format
     * @phpstan-param ContextOptions $context Options normalizers have access to
     *
     * @psalm-return (TType is class-string<TObject> ? TObject : mixed)
     *
     * @phpstan-return ($type is class-string<TObject> ? TObject : mixed)
     *
     * @throws ExceptionInterface
     */
    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed;

    /**
     * @phpstan-param ContextOptions $context Options normalizers have access to
     *
     * @phpstan-return array<mixed>|string|int|float|bool|null
     * @throws ExceptionInterface
     */
    public function normalize(mixed $data, array $context): array|string|int|float|bool|null;

    /**
     * Denormalized data into the given type.
     *
     * @template TObject of object
     * @template TType of string|class-string<TObject>
     *
     * @phpstan-param mixed $data
     * @phpstan-param TType $type
     * @phpstan-param ContextOptions $context Options normalizers have access to
     *
     * @psalm-return (TType is class-string<TObject> ? TObject : mixed)
     *
     * @phpstan-return ($type is class-string<TObject> ? TObject : mixed)
     *
     * @throws ExceptionInterface
     */
    public function denormalize(mixed $data, ?string $type, array $context = []): mixed;
}
