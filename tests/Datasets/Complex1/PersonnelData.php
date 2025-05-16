<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

readonly class PersonnelData
{
    public function __construct(
        public string $personnelExternalId,
        public string $role,
        public string $givenName,
        public string $familyName,
    ) {}
}
