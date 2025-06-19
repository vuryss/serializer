<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

readonly class ShipmentDetails
{
    /**
     * @param SupplementaryData[] $supplementaryData
     * @param ShipmentWaypoint[]  $shipmentWaypoints
     * @param RecordInstance[]    $shipmentRecords
     * @param DataFile[]          $dataFiles
     */
    public function __construct(
        public string $clientExternalId,
        public array $supplementaryData,
        public array $shipmentWaypoints,
        public ShipmentActivityType $activityType,
        public int $sequenceNumber,
        public string $shipmentExternalId,
        public string $locationExternalId,
        public ?string $agreementNumber = null,
        public ?string $trackingNumber = null,
        public ?string $orderReference = null,
        public array $shipmentRecords = [],
        public array $dataFiles = [],
        public ?string $itemExternalId = null,
    ) {}
}
