<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;
use Vuryss\Serializer\Context;

class NullValues
{
    public ?string $nullableString = null;

    #[SerializerContext(context: [Context::SKIP_NULL_VALUES => false])]
    public ?string $alwaysEnabledNull = null;

    #[SerializerContext(context: [Context::SKIP_NULL_VALUES => true])]
    public ?string $alwaysDisabledNull = null;

    /**
     * @var null
     */
    public null $nullValue = null;

    public function __construct(
        public ?int $nullableInt = null,
    ) {}
}
