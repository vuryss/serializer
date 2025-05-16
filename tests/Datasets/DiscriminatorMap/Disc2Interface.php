<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\DiscriminatorMap;

use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

#[DiscriminatorMap(
    typeProperty: 'key',
    mapping: [
        'type1' => Type1::class,
        'type2' => Type2::class,
    ]
)]
interface Disc2Interface {}
