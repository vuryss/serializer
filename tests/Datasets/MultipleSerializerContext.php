<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;

class MultipleSerializerContext
{
    #[SerializerContext(name: 'bla')]
    #[SerializerContext(name: 'invalid')]
    public string $name = 'Test';
}
