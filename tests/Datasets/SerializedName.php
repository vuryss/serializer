<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;

class SerializedName
{
    #[SerializerContext(name: 'changedPropertyName')]
    public string $propertyName = 'value';
}
