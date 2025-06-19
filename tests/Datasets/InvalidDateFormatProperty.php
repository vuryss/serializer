<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Context;

class InvalidDateFormatProperty
{
    #[SerializerContext(context: [
        Context::DATETIME_FORMAT => 123,
    ])]
    public \DateTime $someDate;
}
