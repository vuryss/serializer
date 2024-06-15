<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\SerializerInterface;

class NullValues
{
    public ?string $nullableString = null;

    #[SerializerContext(attributes: [SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES => false])]
    public ?string $alwaysEnabledNull = null;

    #[SerializerContext(attributes: [SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES => true])]
    public ?string $alwaysDisabledNull = null;

    public function __construct(
        public ?int $nullableInt = null,
    ) {}
}
