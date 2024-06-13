<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

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
}
