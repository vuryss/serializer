<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

enum WriteAccess: string
{
    case CONSTRUCTOR = 'constructor';
    case DIRECT = 'direct';
    case SETTER = 'setter';
    case NONE = 'none';
}
