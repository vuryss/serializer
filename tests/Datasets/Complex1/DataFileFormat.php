<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

enum DataFileFormat: string
{
    case TYPE_PDF = 'pdf';
    case TYPE_PNG = 'png';
    case TYPE_JPG = 'jpg';
    case TYPE_JPEG = 'jpeg';
}
