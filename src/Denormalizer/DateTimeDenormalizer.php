<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Exception\InvalidAttributeUsageException;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Path;
use Vuryss\Serializer\SerializerInterface;

class DateTimeDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, DataType $type, Denormalizer $denormalizer, Path $path): \DateTimeInterface
    {
        assert(is_string($data));

        $format = $type->attributes[SerializerInterface::ATTRIBUTE_DATETIME_FORMAT] ?? \DateTimeInterface::RFC3339;

        if (!is_string($format)) {
            throw new InvalidAttributeUsageException('DateTime format attribute must be a string');
        }

        $className = match ($type->className) {
            \DateTimeImmutable::class => \DateTimeImmutable::class,
            \DateTime::class => \DateTime::class,
            default => throw new \InvalidArgumentException('Unsupported class name'),
        };

        $dateTime = $className::createFromFormat($format, $data);

        if (false === $dateTime) {
            throw new DeserializationImpossibleException(sprintf(
                'Cannot denormalize data "%s" at path "%s" into DateTimeImmutable',
                $data,
                $path->toString(),
            ));
        }

        return $dateTime;
    }

    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return is_string($data)
            && BuiltInType::OBJECT === $type->type
            && is_a($type->className, \DateTimeInterface::class, true)
        ;
    }
}
