<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

enum ReadAccess: string
{
    case DIRECT = 'direct';
    case GETTER = 'getter';
    case NONE = 'none';
}
