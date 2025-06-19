<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

use Symfony\Component\TypeInfo\Type;
use Symfony\Component\TypeInfo\TypeIdentifier;
use Vuryss\Serializer\Attribute\DiscriminatorMap;
use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Exception\MetadataExtractionException;

/**
 * Maps between Symfony's Property Info's Types and internal Type representations.
 */
readonly class TypeMapper
{
    private const array INTERFACE_OVERWRITE = [
        \DateTimeInterface::class => \DateTime::class,
    ];

    /**
     * @return array<DataType>
     * @throws MetadataExtractionException
     */
    public function mapTypes(
        Type $type,
        SerializerContext $serializerContext
    ): array {
        switch ($type::class) {
            case Type\NullableType::class:
                return [
                    new DataType(type: BuiltInType::NULL, context: $serializerContext->context),
                    ...$this->mapTypes(type: $type->getWrappedType(), serializerContext: $serializerContext),
                ];

            case Type\UnionType::class:
                return array_merge(
                    ...array_map(
                        callback: fn(Type $t): array
                        => $this->mapTypes(type: $t, serializerContext: $serializerContext),
                        array: $type->getTypes()
                    )
                );

            case Type\ObjectType::class:
                /** @var class-string $className */
                $className = $type->getClassName();
                $reflectionClass = Util::reflectionClass(class: $className);

                if ($reflectionClass->isAbstract() || $reflectionClass->isInterface()) {
                    if (isset(self::INTERFACE_OVERWRITE[$className])) {
                        return [
                            new DataType(
                                type: BuiltInType::OBJECT,
                                className: self::INTERFACE_OVERWRITE[$className],
                                context: $serializerContext->context
                            ),
                        ];
                    }

                    if (null !== $serializerContext->typeMap) {
                        return [
                            new DataType(
                                type: BuiltInType::INTERFACE,
                                className: $className,
                                typeMap: $serializerContext->typeMap,
                                context: $serializerContext->context,
                            ),
                        ];
                    }

                    $discriminatorMap = $this->extractDiscriminatorMap(reflectionClass: $reflectionClass);

                    return [
                        new DataType(
                            type: BuiltInType::INTERFACE,
                            className: $className,
                            typeMap: $discriminatorMap ? [$discriminatorMap->field => $discriminatorMap->map] : [],
                            context: $serializerContext->context,
                        ),
                    ];
                }

                return [new DataType(type: BuiltInType::OBJECT, className: $className, context: $serializerContext->context)];

            case Type\BuiltinType::class:
                return match ($type->getTypeIdentifier()) {
                    TypeIdentifier::OBJECT => [
                        new DataType(type: BuiltInType::OBJECT, context: $serializerContext->context),
                    ],
                    TypeIdentifier::STRING => [
                        new DataType(type: BuiltInType::STRING, context: $serializerContext->context),
                    ],
                    TypeIdentifier::INT => [
                        new DataType(type: BuiltInType::INTEGER, context: $serializerContext->context),
                    ],
                    TypeIdentifier::BOOL => [
                        new DataType(type: BuiltInType::BOOLEAN, context: $serializerContext->context),
                    ],
                    TypeIdentifier::FLOAT => [
                        new DataType(type: BuiltInType::FLOAT, context: $serializerContext->context),
                    ],
                    TypeIdentifier::MIXED => [
                        new DataType(type: BuiltInType::MIXED, context: $serializerContext->context),
                    ],
                    TypeIdentifier::NULL => [
                        new DataType(type: BuiltInType::NULL, context: $serializerContext->context),
                    ],
                    default => throw new MetadataExtractionException(sprintf(
                        'Unsupported built-in type: %s',
                        $type->getTypeIdentifier()->value
                    )),
                };

            case Type\CollectionType::class:
                // TODO: Support associative arrays
                return [
                    new DataType(
                        type: BuiltInType::ARRAY,
                        listType: $this->mapTypes(
                            type: $type->getCollectionValueType(),
                            serializerContext: $serializerContext
                        ),
                        context: $serializerContext->context
                    ),
                ];

            case Type\BackedEnumType::class:
                /** @var class-string $className */
                $className = $type->getClassName();

                return [new DataType(type: BuiltInType::ENUM, className: $className, context: $serializerContext->context)];

            case Type\EnumType::class:
                throw new MetadataExtractionException(sprintf(
                    'Class "%s" is not a backed enum. Cannot denormalize into enum that has no backing type',
                    $type->getClassName(),
                ));

            case Type\TemplateType::class:
                return [new DataType(type: BuiltInType::OBJECT, context: $serializerContext->context)];

        }

        throw new MetadataExtractionException(sprintf('Unsupported type: %s', $type::class));
    }

    /**
     * @template T of object
     *
     * @param \ReflectionClass<T> $reflectionClass
     *
     * @throws MetadataExtractionException
     */
    private function extractDiscriminatorMap(\ReflectionClass $reflectionClass): ?DiscriminatorMapMetadata
    {
        $discriminatorMap = $reflectionClass->getAttributes(DiscriminatorMap::class);

        if (1 === count($discriminatorMap)) {
            $discriminatorMap = $discriminatorMap[0]->newInstance();

            return new DiscriminatorMapMetadata(
                field: $discriminatorMap->typeProperty,
                map: $discriminatorMap->mapping,
            );
        }

        $discriminatorMap = $reflectionClass->getAttributes(\Symfony\Component\Serializer\Attribute\DiscriminatorMap::class);

        if (1 === count($discriminatorMap)) {
            $discriminatorMap = $discriminatorMap[0]->newInstance();

            return new DiscriminatorMapMetadata(
                field: $discriminatorMap->getTypeProperty(),
                /* @phpstan-ignore-next-line */
                map: $discriminatorMap->getMapping(),
            );
        }


        return null;
    }
}
