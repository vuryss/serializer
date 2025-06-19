<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

readonly class FacilityDetails
{
    public function __construct(
        public string $locationExternalId,
        public bool $requiresWeighting,
        public string $facilityExternalId,
        public string $facilityName,

        /**
         * @var ResourceOption[]
         */
        public array $resourceOptions = [],
    ) {}
}
