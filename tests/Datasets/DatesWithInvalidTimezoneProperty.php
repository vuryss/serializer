<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;

class DatesWithInvalidTimezoneProperty
{
    #[SerializerContext(datetimeTargetTimezone: 'Another/InvalidZone')]
    public \DateTimeInterface $dateTimeFormat1;
}
