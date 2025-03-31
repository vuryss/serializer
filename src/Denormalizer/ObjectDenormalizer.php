<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\WriteAccess;
use Vuryss\Serializer\Path;
use Vuryss\Serializer\SerializerException;
use Vuryss\Serializer\SerializerInterface;

class ObjectDenormalizer implements DenormalizerInterface
{
    private const array UNSUPPORTED_CLASS_NAMES = [
        \DateTime::class            => true,
        \DateTimeImmutable::class   => true,
        \DateTimeInterface::class   => true,
    ];

    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $attributes = [],
    ): object {
        assert(is_array($data) && null !== $type->className && class_exists($type->className));
        $className = $type->className;

        $classMetadata = $denormalizer->getMetadataExtractor()->extractClassMetadata($className);
        $constructorParameters = [];
        $directAssignmentProperties = [];
        $setterProperties = [];
        /** @var null|string[] $groups */
        $groups = $attributes[SerializerInterface::ATTRIBUTE_GROUPS] ?? null;

        foreach ($classMetadata->properties as $name => $propertyMetadata) {
            if (
                $propertyMetadata->ignore
                || !array_key_exists($propertyMetadata->serializedName, $data)
                || (null !== $groups && [] === array_intersect($groups, $propertyMetadata->groups))
            ) {
                continue;
            }

            $path->pushObjectProperty($propertyMetadata->serializedName);

            try {
                $value = $this->tryToDenormalize(
                    $data[$propertyMetadata->serializedName],
                    $propertyMetadata->types,
                    $denormalizer,
                    $path,
                    $attributes,
                );
            } finally {
                $path->pop();
            }

            switch ($propertyMetadata->writeAccess) {
                case WriteAccess::NONE:
                    break;
                case WriteAccess::CONSTRUCTOR:
                    $constructorParameters[$name] = $value;
                    break;
                case WriteAccess::DIRECT:
                    $directAssignmentProperties[$name] = $value;
                    break;
                case WriteAccess::SETTER:
                    assert(null !== $propertyMetadata->setterMethod);
                    $setterProperties[$propertyMetadata->setterMethod] = $value;
                    break;
            }
        }

        $instance = count($constructorParameters) > 0
            ? $this->initializeWithConstructor($className, $classMetadata->constructor, $constructorParameters)
            : $this->initializeWithConstructor($className, $classMetadata->constructor, []);

        foreach ($directAssignmentProperties as $name => $value) {
            $instance->{$name} = $value;
        }

        foreach ($setterProperties as $setterMethod => $value) {
            $instance->{$setterMethod}($value);
        }

        return $instance;
    }

    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return
            is_array($data)
            && BuiltInType::OBJECT === $type->type
            && null !== $type->className
            && !isset(self::UNSUPPORTED_CLASS_NAMES[$type->className])
        ;
    }

    /**
     * @param class-string $className
     * @param array<string, mixed> $constructorParameters
     *
     * @throws SerializerException
     */
    private function initializeWithConstructor(
        string $className,
        \Vuryss\Serializer\Metadata\ConstructorMetadata $constructor,
        array $constructorParameters
    ): object {
        $constructorArguments = [];

        foreach ($constructor->arguments as $argument) {
            if (array_key_exists($argument->name, $constructorParameters)) {
                $constructorArguments[] = $constructorParameters[$argument->name];
                unset($constructorParameters[$argument->name]);
                continue;
            }

            if (!$argument->hasDefaultValue) {
                throw new DeserializationImpossibleException(
                    sprintf('Missing required constructor argument "%s" for class "%s"', $argument->name, $className)
                );
            }

            $constructorArguments[] = $argument->defaultValue;
        }

        return new $className(...$constructorArguments);
    }

    /**
     * @param array<DataType> $types
     * @param array<string, scalar|string[]> $attributes
     *
     * @throws SerializerException
     */
    private function tryToDenormalize(
        mixed $value,
        array $types,
        Denormalizer $denormalizer,
        Path $path,
        array $attributes = [],
    ): mixed {
        $lastException = null;

        foreach ($types as $type) {
            try {
                return $denormalizer->denormalize($value, $type, $path, $attributes);
            } catch (SerializerException $e) {
                $lastException = $e;
                continue;
            }
        }

        if (1 === count($types) && null !== $lastException) {
            throw $lastException;
        }

        throw new DeserializationImpossibleException(sprintf(
            'Cannot denormalize value "%s" at path "%s" into any of the given types',
            get_debug_type($value),
            $path->toString()
        ), previous: $lastException);
    }
}
