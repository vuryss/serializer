<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex2;

enum FuelType: string
{
    case PETROL = 'petrol';
    case DIESEL = 'diesel';
    case ELECTRIC = 'electric';
    case HYBRID = 'hybrid';
    case HYDROGEN = 'hydrogen';
}
