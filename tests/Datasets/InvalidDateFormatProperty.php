<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\SerializerInterface;

class InvalidDateFormatProperty
{
    #[SerializerContext(attributes: [
        SerializerInterface::ATTRIBUTE_DATETIME_FORMAT => 123,
    ])]
    public \DateTime $someDate;
}
