<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

interface SerializerInterface
{
    public const string ATTRIBUTE_DATETIME_FORMAT = 'datetime-format';
    public const string ATTRIBUTE_SKIP_NULL_VALUES = 'skip-null-values';
}
