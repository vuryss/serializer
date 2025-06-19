<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Context;

class Dates
{
    #[SerializerContext(
        context: [
            Context::DATETIME_FORMAT => 'm/d/Y',
            Context::DATETIME_FORMAT_STRICT => true,
        ]
    )]
    public \DateTime $uglyUsaDate;

    #[SerializerContext(context: [Context::DATETIME_FORMAT => 'Y-m-d'])]
    public \DateTimeImmutable $immutableDate;

    #[SerializerContext(context: [Context::DATETIME_FORMAT => \DateTimeInterface::RFC3339_EXTENDED])]
    public \DateTime $dateTimeFormat1;

    public \DateTimeImmutable $globalDateTimeFormat;

    public \DateTimeInterface $interfaceDateTime;
}
