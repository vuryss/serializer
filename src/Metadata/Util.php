<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

use Vuryss\Serializer\Exception\MetadataExtractionException;

readonly class Util
{
    /**
     * @template T of object
     *
     * @phpstan-param class-string<T>|T $class
     *
     * @return \ReflectionClass<T>
     *
     * @throws MetadataExtractionException
     */
    public static function reflectionClass(string|object $class): \ReflectionClass
    {
        try {
            return new \ReflectionClass($class);
        } catch (\ReflectionException $e) { // @phpstan-ignore-line
            throw new MetadataExtractionException(
                sprintf('Class "%s" does not exist.', $class),
                previous: $e,
            );
        }
    }

    /**
     * @template T of object
     *
     * @phpstan-param \ReflectionClass<T> $reflectionClass
     *
     * @throws MetadataExtractionException
     */
    public static function reflectionProperty(\ReflectionClass $reflectionClass, string $propertyName): \ReflectionProperty
    {
        try {
            return $reflectionClass->getProperty($propertyName);
        } catch (\ReflectionException $e) {
            throw new MetadataExtractionException(
                message: sprintf('Property "%s" of class "%s" does not exist.', $propertyName, $reflectionClass->getName()),
                previous: $e
            );
        }
    }
}
