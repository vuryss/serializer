<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

use Symfony\Component\TypeInfo\Type;
use Symfony\Component\TypeInfo\TypeIdentifier;

enum BuiltInType: string
{
    case NULL = 'null';
    case BOOLEAN = 'boolean';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case STRING = 'string';
    case ARRAY = 'array';
    case OBJECT = 'object';
    case RESOURCE = 'resource';
    case MIXED = 'mixed';
    case ENUM = 'enum';
    case INTERFACE = 'interface';
    case UNKNOWN = 'unknown';

    public static function fromTypeIdentifier(Type $type): self
    {
        assert($type instanceof Type\BuiltinType);

        return match ($type->getTypeIdentifier()) {
            TypeIdentifier::NULL => self::NULL,
            TypeIdentifier::BOOL => self::BOOLEAN,
            TypeIdentifier::INT => self::INTEGER,
            TypeIdentifier::FLOAT => self::FLOAT,
            TypeIdentifier::STRING => self::STRING,
            TypeIdentifier::ARRAY => self::ARRAY,
            TypeIdentifier::OBJECT => self::OBJECT,
            TypeIdentifier::RESOURCE => self::RESOURCE,
            TypeIdentifier::MIXED => self::MIXED,
            default => throw new \InvalidArgumentException('Unsupported type identifier: ' . $type->getTypeIdentifier()->value),
        };
    }
}
