<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\SerializerInterface;

class Dates
{
    #[SerializerContext(attributes: [SerializerInterface::ATTRIBUTE_DATETIME_FORMAT => 'm/d/Y'])]
    public \DateTime $uglyUsaDate;

    #[SerializerContext(attributes: [SerializerInterface::ATTRIBUTE_DATETIME_FORMAT => 'Y-m-d'])]
    public \DateTimeImmutable $immutableDate;

    #[SerializerContext(attributes: [SerializerInterface::ATTRIBUTE_DATETIME_FORMAT => \DateTimeInterface::RFC3339_EXTENDED])]
    public \DateTime $dateTimeFormat1;

    public \DateTimeImmutable $globalDateTimeFormat;

    public \DateTimeInterface $interfaceDateTime;
}
