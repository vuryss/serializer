<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

use Symfony\Component\PropertyInfo\Type;
use Vuryss\Serializer\Attribute\DiscriminatorMap;
use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Exception\MetadataExtractionException;
use Vuryss\Serializer\Exception\UnsupportedType;

/**
 * Maps between Symfony's Property Info's Types and internal Type representations.
 */
readonly class TypeMapper
{
    private const array INTERFACE_OVERWRITE = [
        \DateTimeInterface::class => \DateTime::class,
    ];

    /**
     * @param array<Type> $propertyInfoTypes
     * @return array<DataType>
     * @throws UnsupportedType|MetadataExtractionException
     */
    public function mapTypes(
        array $propertyInfoTypes,
        \ReflectionProperty $reflectionProperty,
        SerializerContext $serializerContext
    ): array {
        $internalTypes = [];
        $hasNullableType = false;
        $hasAlreadyNullAsType = false;

        foreach ($propertyInfoTypes as $propertyInfoType) {
            $internalType = $this->mapToInternalType(
                $propertyInfoType,
                $reflectionProperty,
                $serializerContext
            );

            if ($propertyInfoType->isNullable()) {
                $hasNullableType = true;
            }

            if (Type::BUILTIN_TYPE_NULL === $propertyInfoType->getBuiltinType()) {
                $hasAlreadyNullAsType = true;
            }

            $internalTypes[] = $internalType;
        }

        if ($hasNullableType && !$hasAlreadyNullAsType) {
            $internalTypes[] = new DataType(BuiltInType::NULL, context: $serializerContext->context);
        }

        return $internalTypes;
    }

    /**
     * @throws MetadataExtractionException
     * @throws UnsupportedType
     */
    private function mapToInternalType(
        Type $propertyInfoType,
        \ReflectionProperty $reflectionProperty,
        SerializerContext $serializerContext
    ): DataType {
        return match ($propertyInfoType->getBuiltinType()) {
            Type::BUILTIN_TYPE_INT => new DataType(BuiltInType::INTEGER, context: $serializerContext->context),
            Type::BUILTIN_TYPE_FLOAT => new DataType(BuiltInType::FLOAT, context: $serializerContext->context),
            Type::BUILTIN_TYPE_STRING => new DataType(BuiltInType::STRING, context: $serializerContext->context),
            Type::BUILTIN_TYPE_BOOL, Type::BUILTIN_TYPE_FALSE, Type::BUILTIN_TYPE_TRUE => new DataType(
                BuiltInType::BOOLEAN,
                context: $serializerContext->context
            ),
            Type::BUILTIN_TYPE_RESOURCE => throw new UnsupportedType(
                sprintf(
                    'Property "%s" of class "%s" has an unsupported type: resource',
                    $reflectionProperty->getName(),
                    $reflectionProperty->getDeclaringClass()->getName(),
                ),
            ),
            Type::BUILTIN_TYPE_OBJECT => $this->mapObjectType($propertyInfoType, $serializerContext),
            Type::BUILTIN_TYPE_ARRAY, Type::BUILTIN_TYPE_ITERABLE => new DataType(
                BuiltInType::ARRAY,
                listType: array_map(
                    fn(Type $type): DataType => $this->mapToInternalType($type, $reflectionProperty, $serializerContext),
                    $propertyInfoType->getCollectionValueTypes(),
                ),
                context: $serializerContext->context
            ),
            Type::BUILTIN_TYPE_NULL => new DataType(BuiltInType::NULL, context: $serializerContext->context),
            Type::BUILTIN_TYPE_CALLABLE => throw new UnsupportedType(
                sprintf(
                    'Property "%s" of class "%s" has an unsupported type: callable',
                    $reflectionProperty->getName(),
                    $reflectionProperty->getDeclaringClass()->getName(),
                ),
            ),
            default => throw new UnsupportedType(
                sprintf(
                    'Property "%s" of class "%s" has an unsupported type: %s',
                    $reflectionProperty->getName(),
                    $reflectionProperty->getDeclaringClass()->getName(),
                    $propertyInfoType->getBuiltinType(),
                ),
            ),
        };
    }

    /**
     * @throws MetadataExtractionException
     */
    private function mapObjectType(Type $propertyInfoType, SerializerContext $serializerContext): DataType
    {
        /** @var class-string|null $className */
        $className = $propertyInfoType->getClassName();

        if (null === $className) {
            return new DataType(BuiltInType::OBJECT, context: $serializerContext->context);
        }

        try {
            $reflectionClass = Util::reflectionClass($className);
        } catch (MetadataExtractionException) {
            // Probably a generic type, return just object
            return new DataType(BuiltInType::OBJECT, $className, context: $serializerContext->context);
        }

        if ($reflectionClass->isEnum()) {
            return new DataType(BuiltInType::ENUM, $className, context: $serializerContext->context);
        }

        if ($reflectionClass->isAbstract() || $reflectionClass->isInterface()) {
            if (isset(self::INTERFACE_OVERWRITE[$className])) {
                return new DataType(BuiltInType::OBJECT, self::INTERFACE_OVERWRITE[$className], context: $serializerContext->context);
            }

            if (null !== $serializerContext->typeMap) {
                return new DataType(
                    BuiltInType::INTERFACE,
                    className: $className,
                    typeMap: $serializerContext->typeMap,
                    context: $serializerContext->context,
                );
            }

            $discriminatorMap = $this->extractDiscriminatorMap($reflectionClass);

            return new DataType(
                BuiltInType::INTERFACE,
                className: $className,
                typeMap: $discriminatorMap ? [$discriminatorMap->field => $discriminatorMap->map] : [],
                context: $serializerContext->context,
            );
        }

        return new DataType(BuiltInType::OBJECT, $className, context: $serializerContext->context);
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
