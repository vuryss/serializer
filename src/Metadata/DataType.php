<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

use Vuryss\Serializer\Exception\DeserializationImpossibleException;
use Vuryss\Serializer\Exception\UnsupportedType;

class DataType
{
    /**
     * @param BuiltInType $type
     * @param class-string|null $className
     * @param array<DataType> $listType
     */
    public function __construct(
        public BuiltInType $type,
        public ?string $className = null,
        public array $listType = [],
    ) {
    }

    /**
     * @throws DeserializationImpossibleException
     */
    public static function fromData(mixed $data): self
    {
        $debugType = get_debug_type($data);

        $type = match ($debugType) {
            'null' => BuiltInType::NULL,
            'bool' => BuiltInType::BOOLEAN,
            'int' => BuiltInType::INTEGER,
            'float' => BuiltInType::FLOAT,
            'string' => BuiltInType::STRING,
            'array' => BuiltInType::ARRAY,
            default => BuiltInType::UNKNOWN,
        };

        if (BuiltInType::UNKNOWN === $type) {
            if (str_starts_with($debugType, 'resource')) {
                throw new DeserializationImpossibleException('Resource type is not supported');
            }

            if (class_exists($debugType)) {
                return new self(BuiltInType::OBJECT, className: $debugType);
            }
        }

        return new self($type);
    }

    /**
     * @throws UnsupportedType
     */
    public static function fromUserType(string $type): self
    {
        $directMatch = BuiltInType::tryFrom($type);

        if ($directMatch) {
            return new self($directMatch);
        }

        if (class_exists($type)) {
            return new self(BuiltInType::OBJECT, className: $type);
        }

        if (str_ends_with($type, '[]')) {
            return new self(BuiltInType::ARRAY, listType: [self::fromUserType(substr($type, 0, -2))]);
        }

        throw new UnsupportedType(sprintf('Unsupported type: %s', $type));
    }
}
