<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\TypeMap;

use Vuryss\Serializer\Attribute\SerializerContext;

class PropertyTypeMap
{
    #[SerializerContext(typeMap: [
        'prop1' => [
            'value1' => PropertyTypeMapImplementation1::class,
        ],
        'prop2' => [
            'value2' => PropertyTypeMapImplementation2::class,
        ],
    ])]
    public PropertyTypeMapInterface $prop;
}
