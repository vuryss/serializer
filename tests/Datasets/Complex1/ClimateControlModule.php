<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

class ClimateControlModule implements ControlModuleInterface
{
    public function __construct(
        public string $type,
        public int $maxTemperature,
    ) {}
}
