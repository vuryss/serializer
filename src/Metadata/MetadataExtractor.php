<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpStanExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Exception\InvalidAttributeUsageException;
use Vuryss\Serializer\Exception\MetadataExtractionException;
use Vuryss\Serializer\ExceptionInterface;
use Vuryss\Serializer\MetadataExtractorInterface;

class MetadataExtractor implements MetadataExtractorInterface
{
    private static ?PropertyInfoExtractorInterface $propertyInfoExtractor = null;

    private static function getPropertyInfoInstance(): PropertyInfoExtractorInterface
    {
        if (null === self::$propertyInfoExtractor) {
            $reflectionExtractor = new ReflectionExtractor();
            $phpDocExtractor = new PhpDocExtractor();
            $phpStanExtractor = new PhpStanExtractor();

            self::$propertyInfoExtractor = new PropertyInfoExtractor(
                listExtractors: [$reflectionExtractor],
                typeExtractors: [$phpStanExtractor, $phpDocExtractor, $reflectionExtractor],
                descriptionExtractors: [$phpDocExtractor],
                accessExtractors: [$reflectionExtractor],
                initializableExtractors: [$reflectionExtractor],
            );
        }

        return self::$propertyInfoExtractor;
    }

    public function __construct(
        private readonly TypeMapper $typeMapper = new TypeMapper(),
    ) {}

    /**
     * @param class-string $class
     *
     * @throws ExceptionInterface
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
     * @throws ExceptionInterface
     */
    private function extractPropertyMetadata(
        \ReflectionProperty $reflectionProperty,
        string $propertyName,
    ): PropertyMetadata {
        $serializerContext = $this->getSerializerContext($reflectionProperty);
        $resolvedType = self::getPropertyInfoInstance()->getType(
            class: $reflectionProperty->getDeclaringClass()->getName(),
            property: $propertyName,
        );

        if (null === $resolvedType) {
            throw new MetadataExtractionException(sprintf(
                'Unable to resolve type for property "%s" of class "%s".',
                $propertyName,
                $reflectionProperty->getDeclaringClass()->getName(),
            ));
        } else {
            $types = $this->typeMapper->mapTypes(
                type: $resolvedType,
                serializerContext: $serializerContext,
            );
        }


        return new PropertyMetadata(
            name: $propertyName,
            serializedName: $serializerContext->name ?? $propertyName,
            types: $types,
            groups: [] === $serializerContext->groups ? ['default'] : $serializerContext->groups,
            context: $serializerContext->context,
            readAccess: $this->getPropertyReadAccess($reflectionProperty),
            writeAccess: $this->getPropertyWriteAccess($reflectionProperty),
            ignore: $serializerContext->ignore,
        );
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
            $isPropertyInConstructor = array_any(
                $constructorParameters,
                static fn(\ReflectionParameter $parameter): bool => $parameter->getName() === $reflectionProperty->getName(),
            );

            if ($isPropertyInConstructor) {
                return WriteAccess::CONSTRUCTOR;
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
                /** @var \Symfony\Component\Serializer\Attribute\SerializedName $snAttribute */
                $snAttribute = $symfonySerializedNameAttribute[0]->newInstance();

                $symfonySerializedName = $snAttribute->serializedName;
            }
        }

        if (class_exists(\Symfony\Component\Serializer\Attribute\Groups::class)) {
            $symfonySerializerGroupsAttribute = $reflectionProperty->getAttributes(
                \Symfony\Component\Serializer\Attribute\Groups::class
            );

            foreach ($symfonySerializerGroupsAttribute as $attribute) {
                /** @var \Symfony\Component\Serializer\Attribute\Groups $groupsAttributeInstance */
                $groupsAttributeInstance = $attribute->newInstance();

                $symfonySerializerGroups = $groupsAttributeInstance->groups;
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

        if (null !== $serializerContext->datetimeTargetTimezone) {
            $serializerContext->context[\Vuryss\Serializer\Context::DATETIME_TARGET_TIMEZONE] = $serializerContext->datetimeTargetTimezone;
        }

        return $serializerContext;
    }
}
