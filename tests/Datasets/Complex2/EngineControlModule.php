<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex2;

class EngineControlModule implements ControlModuleInterface
{
    public function __construct(
        public string $type,
        public FuelType $fuelType,
    ) {}
}
