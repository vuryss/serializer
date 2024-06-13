<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Denormalizer;
use Vuryss\Serializer\DenormalizerInterface;
use Vuryss\Serializer\Exception\InvalidTypeException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\BuiltInType;

class BasicTypesDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, DataType $type, Denormalizer $denormalizer): mixed
    {
        return match ($type->type) {
            BuiltInType::STRING => is_string($data) ? $data : throw InvalidTypeException::create($data, 'string'),
            BuiltInType::INTEGER => is_int($data) ? $data : throw InvalidTypeException::create($data, 'integer'),
            BuiltInType::FLOAT => is_float($data) || is_int($data) ? $data : throw InvalidTypeException::create($data, 'float'),
            BuiltInType::BOOLEAN => is_bool($data) ? $data : throw InvalidTypeException::create($data, 'boolean'),
            BuiltInType::NULL => null === $data ? null : throw InvalidTypeException::create($data, 'null'),
            default => throw new \InvalidArgumentException('Unsupported type'),
        };
    }

    public function supportsDenormalization(mixed $data, DataType $type): bool
    {
        return match ($type->type) {
            BuiltInType::STRING, BuiltInType::INTEGER, BuiltInType::FLOAT, BuiltInType::BOOLEAN, BuiltInType::NULL => true,
            default => false,
        };
    }
}
