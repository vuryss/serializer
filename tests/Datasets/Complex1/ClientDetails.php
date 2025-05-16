<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

readonly class ClientDetails
{
    public function __construct(
        public string $clientName,
        public string $clientExternalId,
    ) {}
}
