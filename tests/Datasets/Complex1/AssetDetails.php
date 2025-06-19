<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

readonly class AssetDetails
{
    public function __construct(
        public string $assetExternalId,
        public string $assetCategory,
        public int $dimensionHeight,
        public int $dimensionLength,
        public int $dimensionWidth,
        public int $mass,
        public int $minCapacity,
        public int $maxCapacity,
        public int $grossVehicleWeightRating,
        public int $maxAxleRating,
        public string $registrationIdentifier,
        public string $attachmentMethod,
    ) {}
}
