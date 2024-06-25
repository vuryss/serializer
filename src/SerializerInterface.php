<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

interface SerializerInterface
{
    public const string ATTRIBUTE_DATETIME_FORMAT = 'datetime-format';
    public const string ATTRIBUTE_SKIP_NULL_VALUES = 'skip-null-values';
    public const string ATTRIBUTE_GROUPS = 'groups';

    /**
     * Serializes data into a string.
     *
     * @param array<string, scalar|string[]> $attributes
     *
     * @throws SerializerException
     */
    public function serialize(mixed $data, array $attributes = []): string;

    /**
     * Deserializes data into the given type.
     *
     * @template TObject of object
     * @template TType of null|class-string<TObject>|string
     *
     * @param array<string, scalar|string[]> $attributes
     *
     * @psalm-param TType $type
     *
     * @psalm-return (TType is class-string<TObject> ? TObject : mixed)
     *
     * @phpstan-return ($type is class-string<TObject> ? TObject : mixed)
     *
     * @throws SerializerException
     */
    public function deserialize(string $data, ?string $type = null, array $attributes = []): mixed;
}
