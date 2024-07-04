<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Exception\InvalidAttributeUsageException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;
use Vuryss\Serializer\SerializerInterface;

class DateTimeDenormalizer implements DenormalizerInterface
{
    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $attributes = [],
    ): \DateTimeInterface {
        if (!is_string($data)) {
            throw new DeserializationImpossibleException(sprintf(
                'Expected date-time string at path "%s", got "%s"',
                $path->toString(),
                gettype($data),
            ));
        }

        $format = $type->attributes[SerializerInterface::ATTRIBUTE_DATETIME_FORMAT] ?? \DateTimeInterface::RFC3339;

        if (!is_string($format)) {
            throw new InvalidAttributeUsageException('DateTime format attribute must be a string');
        }

        $className = match ($type->className) {
            \DateTimeImmutable::class => \DateTimeImmutable::class,
            \DateTime::class, \DateTimeInterface::class => \DateTime::class,
            default => throw new \InvalidArgumentException('Unsupported class name'),
        };

        $dateTime = $className::createFromFormat($format, $data);

        // Fallback to automatic parsing
        if (false === $dateTime) {
            try {
                $dateTime = new $className($data);
            } catch (\Exception $e) {
                throw new DeserializationImpossibleException(sprintf(
                    'Cannot denormalize date string "%s" at path "%s" into DateTimeImmutable. Expected format: "%s"',
                    $data,
                    $path->toString(),
                    $format,
                ), previous: $e);
            }
        }

        return $dateTime;
    }

    public function getSupportedClassNames(): array
    {
        return [\DateTimeInterface::class, \DateTimeImmutable::class, \DateTime::class];
    }
}
