<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Context;
use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Exception\InvalidAttributeUsageException;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;

class DateTimeDenormalizer implements DenormalizerInterface
{
    private const array SUPPORTED_CLASS_NAMES = [
        \DateTime::class            => true,
        \DateTimeImmutable::class   => true,
        \DateTimeInterface::class   => true,
    ];

    public function denormalize(
        mixed $data,
        DataType $type,
        Denormalizer $denormalizer,
        Path $path,
        array $context = [],
    ): \DateTimeInterface {
        assert(is_string($data));

        $format = $type->context[Context::DATETIME_FORMAT] ?? \DateTimeInterface::RFC3339;
        $isStrict = $type->context[Context::DATETIME_FORMAT_STRICT] ?? false;

        if (!is_string($format)) {
            throw new InvalidAttributeUsageException('DateTime format attribute must be a string');
        }

        $className = match ($type->className) {
            \DateTimeImmutable::class => \DateTimeImmutable::class,
            \DateTime::class, \DateTimeInterface::class => \DateTime::class,
            default => throw new \InvalidArgumentException('Unsupported class name'),
        };

        $dateTime = $className::createFromFormat($format, $data);

        if (false === $dateTime && $isStrict) {
            throw new DeserializationImpossibleException(sprintf(
                'Cannot denormalize date string "%s" at path "%s" into %s. Expected format: "%s"',
                $data,
                $path->toString(),
                $className,
                $format,
            ));
        }

        // Fallback to automatic parsing
        if (false === $dateTime) {
            try {
                $dateTime = new $className($data);
            } catch (\Exception $e) {
                throw new DeserializationImpossibleException(sprintf(
                    'Cannot denormalize date string "%s" at path "%s" into %s. Expected format: "%s"',
                    $data,
                    $path->toString(),
                    $className,
                    $format,
                ), previous: $e);
            }
        }

        // Apply timezone conversion if specified
        $targetTimezone = $type->context[Context::DATETIME_TARGET_TIMEZONE] ?? $context[Context::DATETIME_TARGET_TIMEZONE] ?? null;

        if (is_string($targetTimezone) && $dateTime instanceof \DateTimeInterface) {
            try {
                $dateTime = $dateTime->setTimezone(new \DateTimeZone($targetTimezone));
            } catch (\Exception $e) {
                // Optionally, handle or log the exception if the timezone string is invalid
                throw new DeserializationImpossibleException(sprintf(
                    'Invalid target timezone string "%s" at path "%s"',
                    $targetTimezone,
                    $path->toString(),
                ), previous: $e);
            }
        }

        return $dateTime;
    }

    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return is_string($data)
            && (BuiltInType::OBJECT === $type->type || BuiltInType::INTERFACE === $type->type)
            && isset(self::SUPPORTED_CLASS_NAMES[$type->className])
        ;
    }
}
