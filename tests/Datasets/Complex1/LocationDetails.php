<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

readonly class LocationDetails
{
    public function __construct(
        public string $nation,
        public string $zipCode,
        public string $municipality,
        public string $region,
        public string $thoroughfare,
        public string $buildingIdentifier,
        public string $contactNumber,
        public string $province,
        public string $locationExternalId,
        public ?string $locationName = null,
    ) {}
}
