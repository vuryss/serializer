<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

readonly class ShipmentWaypoint
{
    /**
     * @param SupplementaryData[] $supplementaryData
     * @param JobActivity[]       $jobActivities
     */
    public function __construct(
        public JobGroupType $waypointType,
        public string $locationExternalId,
        public array $supplementaryData,
        public array $jobActivities,
    ) {}
}
