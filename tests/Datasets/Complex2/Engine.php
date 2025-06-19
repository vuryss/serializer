<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex2;

use Vuryss\Serializer\Attribute\SerializerContext;

class Engine
{
    private int $cylinders;

    /**
     * @var int|float|string|Airbag[]|FuelType[]|string[]|Engine|null
     */
    #[SerializerContext(name: 'multiTypeField')]
    public int|null|float|string|array|object $multiValueField;

    public function __construct(
        #[SerializerContext(name: 'engineCode')]
        private readonly string $code,
        public FuelType $fuelType,
    ) {}

    public function setCylinders(int $cylinders): void
    {
        $this->cylinders = $cylinders;
    }

    public function getCylinders(): int
    {
        return $this->cylinders;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
