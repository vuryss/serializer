<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

interface SerializableInterface
{
    public static function getJsonSerialized(): string;
}
