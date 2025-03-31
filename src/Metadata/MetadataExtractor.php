<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

use Symfony\Component\TypeInfo\Exception\UnsupportedException;
use Symfony\Component\TypeInfo\Type;
use Symfony\Component\TypeInfo\TypeIdentifier;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Vuryss\Serializer\Attribute\DiscriminatorMap;
use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Exception\InvalidAttributeUsageException;
use Vuryss\Serializer\Exception\MetadataExtractionException;
use Vuryss\Serializer\MetadataExtractorInterface;
use Vuryss\Serializer\SerializerException;

class MetadataExtractor implements MetadataExtractorInterface
{
    private const array INTERFACE_OVERWRITE = [
        \DateTimeInterface::class => \DateTime::class,
    ];

    private static ?TypeResolver $typeResolver = null;

    private static function getTypeResolver(): TypeResolver
    {
        if (null === self::$typeResolver) {
            self::$typeResolver = TypeResolver::create();
        }

        return self::$typeResolver;
    }

    /**
     * @param class-string $class
     *
     * @throws SerializerException
     */
    public function extractClassMetadata(string $class): ClassMetadata
    {
        $reflectionClass = Util::reflectionClass($class);
        $properties = [];

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            $properties[$propertyName] = $this->extractPropertyMetadata(
                reflectionProperty: Util::reflectionProperty($reflectionClass, $propertyName),
                propertyName: $propertyName
            );
        }

        return new ClassMetadata(
            properties: $properties,
            constructor: $this->extractConstructorMetadata($reflectionClass),
        );
    }

    /**
     * @throws SerializerException
     */
    private function extractPropertyMetadata(
        \ReflectionProperty $reflectionProperty,
        string $propertyName,
    ): PropertyMetadata {
        $serializerContext = $this->getSerializerContext($reflectionProperty);
        try {
            $resolvedType = self::getTypeResolver()->resolve($reflectionProperty);
        } catch (UnsupportedException $e) {
            throw new MetadataExtractionException($e->getMessage(), previous: $e);
        }

        return new PropertyMetadata(
            name: $propertyName,
            serializedName: $serializerContext->name ?? $propertyName,
            types: $this->mapType($resolvedType, $serializerContext),
            groups: [] === $serializerContext->groups ? ['default'] : $serializerContext->groups,
            attributes: $serializerContext->attributes,
            readAccess: $this->getPropertyReadAccess($reflectionProperty),
            writeAccess: $this->getPropertyWriteAccess($reflectionProperty),
            ignore: $serializerContext->ignore,
        );
    }

    /**
     * @return array<DataType>
     * @throws MetadataExtractionException
     */
    private function mapType(Type $type, SerializerContext $serializerContext): array
    {
        switch ($type::class) {
            case Type\BackedEnumType::class:
                $className = $type->getClassName();

                if (!class_exists($className)) {
                    throw new MetadataExtractionException(sprintf(
                        'Backed enum "%s" does not exist.', $className,
                    ));
                }

                return [
                    new DataType(
                        type: BuiltInType::ENUM,
                        className: $className,
                        attributes: $serializerContext->attributes
                    )
                ];

            case Type\EnumType::class:
                throw new MetadataExtractionException(
                    sprintf(
                        'Non-backed enum type "%s" is not supported. Use a backed enum instead.',
                        $type->getClassName(),
                    )
                );

            case Type\ObjectType::class:
                return [
                    $this->mapObjectType($type, $serializerContext)
                ];

            case Type\BuiltinType::class:
                return [
                    new DataType(
                        type: BuiltInType::fromTypeIdentifier($type),
                        className: null,
                        attributes: $serializerContext->attributes
                    )
                ];

            case Type\CollectionType::class:
                return [
                    new DataType(
                        type: BuiltInType::ARRAY,
                        listType: $this->mapType($type->getWrappedType(), $serializerContext),
                        attributes: $serializerContext->attributes
                    )
                ];

            case Type\GenericType::class:
                $wrappedType = $type->getWrappedType();
                $variableTypes = $type->getVariableTypes();

                if (
                    $wrappedType instanceof Type\BuiltinType
                    /** @phpstan-ignore-next-line */
                    && (TypeIdentifier::ARRAY === $wrappedType->getTypeIdentifier() || TypeIdentifier::ITERABLE === $wrappedType->getTypeIdentifier())
                ) {
                    return $this->mapType($variableTypes[1], $serializerContext);
                }

                throw new MetadataExtractionException(sprintf(
                    'Generic type "%s" is not supported. Use a collection type instead.',
                    $type
                ));

            case Type\IntersectionType::class:
                throw new MetadataExtractionException(
                    sprintf('Intersection type "%s" is not supported.', $type)
                );

            case Type\NullableType::class:
                return [
                    new DataType(
                        type: BuiltInType::NULL,
                        attributes: $serializerContext->attributes
                    ),
                    ...$this->mapType($type->getWrappedType(), $serializerContext),
                ];

            case Type\TemplateType::class:
                return [
                    new DataType(
                        type: BuiltInType::OBJECT,
                        attributes: $serializerContext->attributes,
                    ),
                ];

            case Type\UnionType::class:
                $types = [];

                foreach ($type->getTypes() as $type) {
                    $types = array_merge($types, $this->mapType($type, $serializerContext));
                }

                return $types;


            default: throw new MetadataExtractionException(sprintf(
                'Type "%s" is not supported.',
                $type::class,
            ));
        }
    }

    /**
     * @template T
     *
     * @param Type\ObjectType<T> $extractedType
     *
     * @throws MetadataExtractionException
     */
    private function mapObjectType(Type\ObjectType $extractedType, SerializerContext $serializerContext): DataType
    {
        /** @var class-string $className */
        $className = $extractedType->getClassName();
        $reflectionClass = Util::reflectionClass($className);

        if ($reflectionClass->isAbstract() || $reflectionClass->isInterface()) {
            if (isset(self::INTERFACE_OVERWRITE[$className])) {
                return new DataType(BuiltInType::OBJECT, self::INTERFACE_OVERWRITE[$className], attributes: $serializerContext->attributes);
            }

            if (null !== $serializerContext->typeMap) {
                return new DataType(
                    BuiltInType::INTERFACE,
                    className: $className,
                    typeMap: $serializerContext->typeMap,
                    attributes: $serializerContext->attributes,
                );
            }

            $discriminatorMap = $this->extractDiscriminatorMap($reflectionClass);

            return new DataType(
                BuiltInType::INTERFACE,
                className: $className,
                typeMap: [$discriminatorMap->field => $discriminatorMap->map],
                attributes: $serializerContext->attributes,
            );
        }

        return new DataType(BuiltInType::OBJECT, $className, attributes: $serializerContext->attributes);
    }

    private function getPropertyReadAccess(\ReflectionProperty $reflectionProperty): ReadAccess
    {
        if ($reflectionProperty->isPublic()) {
            return ReadAccess::DIRECT;
        }

        $getterMethodName = 'get' . ucfirst($reflectionProperty->getName());

        if ($reflectionProperty->getDeclaringClass()->hasMethod($getterMethodName)) {
            return ReadAccess::GETTER;
        }

        return ReadAccess::NONE;
    }

    private function getPropertyWriteAccess(\ReflectionProperty $reflectionProperty): WriteAccess
    {
        $constructor = $reflectionProperty->getDeclaringClass()->getConstructor();

        if (null !== $constructor && $constructor->isPublic()) {
            $constructorParameters = $constructor->getParameters();

            foreach ($constructorParameters as $constructorParameter) {
                if ($constructorParameter->getName() === $reflectionProperty->getName()) {
                    return WriteAccess::CONSTRUCTOR;
                }
            }
        }

        if ($reflectionProperty->isPublic() && !$reflectionProperty->isReadOnly()) {
            return WriteAccess::DIRECT;
        }

        $setterMethodName = 'set' . ucfirst($reflectionProperty->getName());

        if ($reflectionProperty->getDeclaringClass()->hasMethod($setterMethodName)) {
            return WriteAccess::SETTER;
        }

        return WriteAccess::NONE;
    }

    /**
     * @param \ReflectionClass<object> $reflectionClass
     */
    private function extractConstructorMetadata(\ReflectionClass $reflectionClass): ConstructorMetadata
    {
        $constructor = $reflectionClass->getConstructor();

        if (null === $constructor) {
            return new ConstructorMetadata(isPublic: false, arguments: []);
        }

        $isPublic = $constructor->isPublic();
        $arguments = [];

        if ($isPublic) {
            foreach ($constructor->getParameters() as $reflectionParameter) {
                $arguments[] = new ArgumentMetadata(
                    name: $reflectionParameter->getName(),
                    hasDefaultValue: $reflectionParameter->isDefaultValueAvailable(),
                    defaultValue: $reflectionParameter->isDefaultValueAvailable() ? $reflectionParameter->getDefaultValue() : null,
                );
            }
        }

        return new ConstructorMetadata(isPublic: $isPublic, arguments: $arguments);
    }

    /**
     * @throws InvalidAttributeUsageException
     */
    public function getSerializerContext(\ReflectionProperty $reflectionProperty): SerializerContext
    {
        $symfonySerializedName = null;
        $symfonySerializerGroups = [];
        $serializerContexts = $reflectionProperty->getAttributes(SerializerContext::class);
        $isSymfonyIgnored = false;

        if (count($serializerContexts) > 1) {
            throw new InvalidAttributeUsageException(
                sprintf(
                    'Property "%s" of class "%s" has more than one SerializerContext attribute',
                    $reflectionProperty->getName(),
                    $reflectionProperty->getDeclaringClass()->getName(),
                ),
            );
        }

        if (class_exists(\Symfony\Component\Serializer\Attribute\SerializedName::class)) {
            $symfonySerializedNameAttribute = $reflectionProperty->getAttributes(
                \Symfony\Component\Serializer\Attribute\SerializedName::class
            );

            if (isset($symfonySerializedNameAttribute[0])) {
                $symfonySerializedName = $symfonySerializedNameAttribute[0]->newInstance()->getSerializedName();
            }
        }

        if (class_exists(\Symfony\Component\Serializer\Attribute\Groups::class)) {
            $symfonySerializerGroupsAttribute = $reflectionProperty->getAttributes(
                \Symfony\Component\Serializer\Attribute\Groups::class
            );

            foreach ($symfonySerializerGroupsAttribute as $attribute) {
                $symfonySerializerGroups = $attribute->newInstance()->getGroups();
            }
        }

        if (class_exists(\Symfony\Component\Serializer\Attribute\Ignore::class)) {
            $symfonySerializerIgnoreAttribute = $reflectionProperty->getAttributes(
                \Symfony\Component\Serializer\Attribute\Ignore::class
            );

            if (isset($symfonySerializerIgnoreAttribute[0])) {
                $isSymfonyIgnored = true;
            }
        }

        $serializerContext = isset($serializerContexts[0]) ? $serializerContexts[0]->newInstance() : new SerializerContext();

        if (null === $serializerContext->name && null !== $symfonySerializedName) {
            $serializerContext->name = $symfonySerializedName;
        }

        if ([] === $serializerContext->groups && [] !== $symfonySerializerGroups) {
            $serializerContext->groups = $symfonySerializerGroups;
        }

        if ($isSymfonyIgnored) {
            $serializerContext->ignore = true;
        }

        return $serializerContext;
    }

    /**
     * @template T of object
     *
     * @param \ReflectionClass<T> $reflectionClass
     *
     * @throws MetadataExtractionException
     */
    private function extractDiscriminatorMap(\ReflectionClass $reflectionClass): DiscriminatorMapMetadata
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


        throw new MetadataExtractionException(
            sprintf(
                'Class "%s" does not have a valid DiscriminatorMap attribute. Cannot resolve data type of abstract class or interface.',
                $reflectionClass->getName(),
            )
        );
    }
}
