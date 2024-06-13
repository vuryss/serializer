<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Exception;

use Vuryss\Serializer\SerializerException;

class InvalidTypeException extends \Exception implements SerializerException
{
    public static function create(mixed $data, string $expectedType): self
    {
        return new self(sprintf('Expected data to be of type "%s", got "%s"', $expectedType, gettype($data)));
    }
}
