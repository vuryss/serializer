<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

enum IntBackedEnum: int
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
}
