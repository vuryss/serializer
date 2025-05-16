<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

class ServiceItem
{
    public function __construct(
        public string $itemNumber,
        public string $description,
        public ?string $jobExternalId,
        public ServiceItemTemplateField $templateField,
        public ?string $measurementUnit = null,
        public string $referenceType = 'itemQuantity',
    ) {}
}
