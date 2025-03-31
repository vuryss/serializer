<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

readonly class InvalidIntersectionTypeWrapper
{
    public Person&Monitor $intersectionType;
}
