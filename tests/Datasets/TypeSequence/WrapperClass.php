<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\TypeSequence;

class WrapperClass
{
    /**
     * @var Car[]|Truck[]
     */
    public array $property;

    /**
     * @var Truck[]|Car[]
     */
    public array $property2;
}
