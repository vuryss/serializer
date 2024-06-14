<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Tests\Datasets\SerializableInterface;

class Car implements SerializableInterface
{
    public string $licensePlate;
    private Engine $engine;
    public int|float $weight;
    public int|float $height;

    /** @var array<Airbag> */
    public array $airbags = [];

    /**
     * @var int|float|string|Airbag[]|string[]|FuelType[]|Engine|null
     */
    #[SerializerContext(name: 'multiTypeField')]
    public int|null|float|string|array|object $multiValueField;

    public function __construct(
        public bool $isReleased,
        private readonly int $horsePower,
    ) {
    }

    public function getHorsePower(): int
    {
        return $this->horsePower;
    }

    public function setEngine(Engine $engine): void
    {
        $this->engine = $engine;
    }

    public function getEngine(): Engine
    {
        return $this->engine;
    }

    public static function getJsonSerialized(): string
    {
        return json_encode([
            'licensePlate' => 'Y6492AH',
            'horsePower' => 150,
            'isReleased' => true,
            'weight' => 1500.5,
            'height' => 123,
            'multiTypeField' => [ // Should be array of Engine
                'cylinders' => 4,
                'engineCode' => 'VTEC',
                'fuelType' => 'diesel',
                'multiTypeField' => ['just-string'], // Should be array of strings
            ],
            'engine' => [
                'cylinders' => 8,
                'engineCode' => 'V8',
                'fuelType' => 'petrol',
                'multiTypeField' => ['diesel'], // Should be array of FuelType
            ],
            'airbags' => [
                ['model' => 'ARG123'],
                ['model' => 'FTP231'],
            ]
        ]);
    }
}
