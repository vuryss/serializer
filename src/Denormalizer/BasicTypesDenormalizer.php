<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Denormalizer;

use Vuryss\Serializer\Exception\InvalidTypeException;
use Vuryss\Serializer\Metadata\DataType;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Path;

class BasicTypesDenormalizer
{
    /**
     * @throws InvalidTypeException
     */
    public function denormalize(
        mixed $data,
        DataType $type,
        Path $path,
    ): mixed {
        return match ($type->type) {
            BuiltInType::STRING => is_string($data) ? $data : $this->error($data, 'string', $path),
            BuiltInType::INTEGER => is_int($data) ? $data : $this->error($data, 'integer', $path),
            BuiltInType::FLOAT => is_float($data) || is_int($data) ? $data : $this->error($data, 'float', $path),
            BuiltInType::BOOLEAN => is_bool($data) ? $data : $this->error($data, 'boolean', $path),
            BuiltInType::NULL => null === $data ? null : $this->error($data, 'null', $path),
            BuiltInType::MIXED => $data,
            default => throw new \InvalidArgumentException('Unsupported type'),
        };
    }

    /**
     * @throws InvalidTypeException
     */
    private function error(mixed $data, string $expectedType, Path $path): mixed
    {
        throw new InvalidTypeException(sprintf(
            'Expected type "%s" at path "%s", got "%s"',
            $expectedType,
            $path->toString(),
            gettype($data)
        ));
    }
}
