<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Exception\DenormalizerNotFoundException;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\MetadataExtractor;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\WriteAccess;
use Vuryss\Serializer\Path;
use Vuryss\Serializer\SerializerException;

class ObjectDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, DataType $type, Denormalizer $denormalizer, Path $path): object
    {
        assert(null !== $type->className && class_exists($type->className));
        $className = $type->className;

        if (!is_array($data)) {
            throw new DeserializationImpossibleException('Data must be an array');
        }

        $metadataExtractor = new MetadataExtractor();
        $classMetadata = $metadataExtractor->extractClassMetadata($className);
        $constructorParameters = [];
        $directAssignmentProperties = [];
        $setterProperties = [];

        foreach ($classMetadata->properties as $name => $propertyMetadata) {
            if (!array_key_exists($propertyMetadata->serializedName, $data)) {
                continue;
            }

            $path->pushObjectProperty($propertyMetadata->serializedName);

            try {
                $value = $this->tryToDenormalize(
                    $data[$propertyMetadata->serializedName],
                    $propertyMetadata->types,
                    $denormalizer,
                    $path
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

        if (count($constructorParameters) > 0) {
            $instance = $this->initializeWithConstructor($className, $classMetadata->constructor, $constructorParameters);
        } else {
            $instance = new $className();
        }

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
        return is_array($data) && BuiltInType::OBJECT === $type->type && null !== $type->className;
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
        }

        return new $className(...$constructorArguments);
    }

    /**
     * @param array<DataType> $types
     *
     * @throws SerializerException
     */
    private function tryToDenormalize(mixed $value, array $types, Denormalizer $denormalizer, Path $path): mixed
    {
        foreach ($types as $type) {
            try {
                return $denormalizer->denormalize($value, $type, $path);
            } catch (SerializerException) {
                continue;
            }
        }

        throw new DeserializationImpossibleException(sprintf(
            'Cannot denormalize value "%s" at path "%s" into any of the given types',
            get_debug_type($value),
            $path->toString()
        ));
    }
}
