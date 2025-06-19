<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;

class DatesWithTimezoneProperty
{
    #[SerializerContext(datetimeTargetTimezone: 'Europe/Berlin')]
    public \DateTimeInterface $dateTimeFormat1;

    public \DateTimeImmutable $globalDateTimeFormat;

    // Other properties from Dates to ensure object can be instantiated if necessary,
    // or if the test relies on their existence for other reasons.
    // If not strictly needed by this specific test, they can be omitted.
    public ?\DateTime $uglyUsaDate = null;
    public ?\DateTimeImmutable $immutableDate = null;
    public ?\DateTimeInterface $interfaceDateTime = null;
}
