<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\WriteAccess;
use Vuryss\Serializer\Path;
use Vuryss\Serializer\SerializerException;
use Vuryss\Serializer\SerializerInterface;

class ObjectDenormalizer
{
    /**
     * @throws SerializerException
     */
    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $attributes = [],
    ): object {
        if (!is_array($data)) {
            throw new DeserializationImpossibleException(sprintf(
                'Expected type "array" at path "%s", got "%s"',
                $path->toString(),
                get_debug_type($data),
            ));
        }

        if (null === $type->className) {
            throw new DeserializationImpossibleException(sprintf(
                'Cannot denormalize data at path "%s" into object because class name cannot be resolved',
                $path->toString(),
            ));
        }

        $classMetadata = $denormalizer->getMetadataExtractor()->extractClassMetadata($type->className);
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
            ? $this->initializeWithConstructor($type->className, $classMetadata->constructor, $constructorParameters)
            : $this->initializeWithConstructor($type->className, $classMetadata->constructor, []);

        foreach ($directAssignmentProperties as $name => $value) {
            $instance->{$name} = $value;
        }

        foreach ($setterProperties as $setterMethod => $value) {
            $instance->{$setterMethod}($value);
        }

        return $instance;
    }

    /**
     * @param class-string $className
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
