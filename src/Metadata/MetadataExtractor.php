<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpStanExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Type;
use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Exception\InvalidAttributeUsageException;
use Vuryss\Serializer\Exception\MetadataExtractionException;
use Vuryss\Serializer\Exception\UnsupportedType;
use Vuryss\Serializer\MetadataExtractorInterface;
use Vuryss\Serializer\SerializerException;

class MetadataExtractor implements MetadataExtractorInterface
{
    private static function getPropertyInfoInstance(): PropertyInfoExtractorInterface
    {
        $reflectionExtractor = new ReflectionExtractor();
        $phpDocExtractor = new PhpDocExtractor();
        $phpStanExtractor = new PhpStanExtractor();

        return new PropertyInfoExtractor(
            listExtractors: [$reflectionExtractor],
            typeExtractors: [$phpStanExtractor, $phpDocExtractor, $reflectionExtractor],
            descriptionExtractors: [$phpDocExtractor],
            accessExtractors: [$reflectionExtractor],
            initializableExtractors: [$reflectionExtractor],
        );
    }

    /**
     * @param class-string $class
     *
     * @throws SerializerException
     */
    public function extractClassMetadata(string $class): ClassMetadata
    {
        $propertyInfoExtractor = self::getPropertyInfoInstance();

        try {
            $reflectionClass = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new MetadataExtractionException(sprintf('Failed to reflect class "%s"', $class), previous: $e);
        }

        $properties = [];

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            try {
                $properties[$propertyName] = $this->extractPropertyMetadata(
                    $propertyInfoExtractor,
                    $reflectionClass->getProperty($propertyName),
                    $propertyName
                );
            } catch (\ReflectionException $e) {
                throw new MetadataExtractionException(
                    sprintf(
                        'Failed to reflect property "%s" of class "%s"',
                        $propertyName,
                        $class,
                    ),
                    previous: $e,
                );
            }
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
        PropertyInfoExtractorInterface $propertyInfoExtractor,
        \ReflectionProperty $reflectionProperty,
        string $propertyName
    ): PropertyMetadata {
        $serializerContexts = $reflectionProperty->getAttributes(SerializerContext::class);

        if (count($serializerContexts) > 1) {
            throw new InvalidAttributeUsageException(
                sprintf(
                    'Property "%s" of class "%s" has more than one SerializerContext attribute',
                    $reflectionProperty->getName(),
                    $reflectionProperty->getDeclaringClass()->getName(),
                ),
            );
        }

        $serializerContext = isset($serializerContexts[0]) ? $serializerContexts[0]->newInstance() : new SerializerContext();

        return new PropertyMetadata(
            name: $propertyName,
            serializedName: $serializerContext->name ?? $propertyName,
            types: $this->resolveType(
                $propertyInfoExtractor,
                $reflectionProperty,
                $propertyName,
                $serializerContext,
            ),
            groups: [] === $serializerContext->groups ? ['default'] : $serializerContext->groups,
            attributes: $serializerContext->attributes,
            readAccess: $this->getPropertyReadAccess($reflectionProperty),
            writeAccess: $this->getPropertyWriteAccess($reflectionProperty),
            ignore: $serializerContext->ignore,
        );
    }

    /**
     * @return DataType[]
     * @throws SerializerException
     */
    private function resolveType(
        PropertyInfoExtractorInterface $propertyInfoExtractor,
        \ReflectionProperty $reflectionProperty,
        string $propertyName,
        SerializerContext $serializerContext,
    ): array {
        $extractedTypes = $propertyInfoExtractor->getTypes(
            $reflectionProperty->getDeclaringClass()->getName(),
            $propertyName
        );

        if (null === $extractedTypes) {
            $reflectionType = $reflectionProperty->getType();

            if ($reflectionType instanceof \ReflectionNamedType && 'mixed' === $reflectionType->getName()) {
                return [new DataType(BuiltInType::MIXED, attributes: $serializerContext->attributes)];
            }

            throw new MetadataExtractionException(
                sprintf(
                    'Failed to extract types of property "%s" of class "%s"',
                    $reflectionProperty->getName(),
                    $reflectionProperty->getDeclaringClass()->getName(),
                ),
            );
        }

        $hasNullableType = false;
        $shouldHaveNullableType = false;
        $mappedTypes = [];

        foreach ($extractedTypes as $extractedType) {
            $mappedType = $this->mapType($extractedType, $reflectionProperty, $serializerContext);

            if (BuiltInType::NULL === $mappedType->type) {
                $hasNullableType = true;
            }

            if ($extractedType->isNullable()) {
                $shouldHaveNullableType = true;
            }

            $mappedTypes[] = $mappedType;
        }

        if (!$hasNullableType && $shouldHaveNullableType) {
            $mappedTypes[] = new DataType(BuiltInType::NULL, attributes: $serializerContext->attributes);
        }

        return $mappedTypes;
    }

    /**
     * @throws UnsupportedType
     */
    private function mapType(
        Type $extractedType,
        \ReflectionProperty $reflectionProperty,
        SerializerContext $serializerContext
    ): DataType {
        return match ($extractedType->getBuiltinType()) {
            Type::BUILTIN_TYPE_INT => new DataType(BuiltInType::INTEGER, attributes: $serializerContext->attributes),
            Type::BUILTIN_TYPE_FLOAT => new DataType(BuiltInType::FLOAT, attributes: $serializerContext->attributes),
            Type::BUILTIN_TYPE_STRING => new DataType(BuiltInType::STRING, attributes: $serializerContext->attributes),
            Type::BUILTIN_TYPE_BOOL, Type::BUILTIN_TYPE_FALSE, Type::BUILTIN_TYPE_TRUE => new DataType(
                BuiltInType::BOOLEAN,
                attributes: $serializerContext->attributes
            ),
            Type::BUILTIN_TYPE_RESOURCE => throw new UnsupportedType(
                sprintf(
                    'Property "%s" of class "%s" has an unsupported type: resource',
                    $reflectionProperty->getName(),
                    $reflectionProperty->getDeclaringClass()->getName(),
                ),
            ),
            Type::BUILTIN_TYPE_OBJECT => $this->mapObjectType($extractedType, $serializerContext),
            Type::BUILTIN_TYPE_ARRAY, Type::BUILTIN_TYPE_ITERABLE => new DataType(
                BuiltInType::ARRAY,
                listType: array_map(
                    fn(Type $type): DataType => $this->mapType($type, $reflectionProperty, $serializerContext),
                    $extractedType->getCollectionValueTypes(),
                ),
                attributes: $serializerContext->attributes
            ),
            Type::BUILTIN_TYPE_NULL => new DataType(BuiltInType::NULL, attributes: $serializerContext->attributes),
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
                    $extractedType->getBuiltinType(),
                ),
            ),
        };
    }

    private function mapObjectType(Type $extractedType, SerializerContext $serializerContext): DataType
    {
        $className = $extractedType->getClassName();

        if (null === $className) {
            return new DataType(BuiltInType::OBJECT, attributes: $serializerContext->attributes);
        }

        if (enum_exists($className)) {
            return new DataType(BuiltInType::ENUM, $className, attributes: $serializerContext->attributes);
        }

        if (interface_exists($className)) {
            return new DataType(
                BuiltInType::INTERFACE,
                className: $className,
                typeMap: $serializerContext->typeMap ?? [],
                attributes: $serializerContext->attributes
            );
        }

        if (class_exists($className)) {
            return new DataType(BuiltInType::OBJECT, $className, attributes: $serializerContext->attributes);
        }

        return new DataType(BuiltInType::OBJECT, attributes: $serializerContext->attributes);
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
}
