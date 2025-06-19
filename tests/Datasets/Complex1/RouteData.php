<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Context;

readonly class RouteData
{
    /**
     * @param PersonnelData[]     $personnel
     * @param LocationDetails[]   $locations
     * @param ClientDetails[]     $clients
     * @param AssetDetails[]      $assets
     * @param ShipmentDetails[]   $shipments
     * @param FacilityDetails[]   $facilities
     * @param string[]            $masterRoutes
     * @param SupplementaryData[] $supplementaryData
     * @param RecordInstance[]    $routeRecords
     * @param DataFile[]          $dataFiles
     */
    public function __construct(
        public array $personnel,
        public array $locations,
        public array $clients,
        public array $assets,
        public array $shipments,
        public string $routeExternalId,
        public string $branchExternalId,
        #[SerializerContext(context: [Context::DATETIME_FORMAT => \DateTimeInterface::ATOM])]
        public \DateTimeImmutable $startTime,
        #[SerializerContext(context: [Context::DATETIME_FORMAT => \DateTimeInterface::ATOM])]
        public \DateTimeImmutable $endTime,
        public string $startLocationExternalId,
        public string $endLocationExternalId,
        public bool $fixedSequence,
        public string $nation,
        public string $routeName,
        public string $interruptionExternalId,
        public array $facilities,
        public array $masterRoutes = [],
        public array $supplementaryData = [],
        public array $routeRecords = [],
        public array $dataFiles = [],
    ) {}
}
