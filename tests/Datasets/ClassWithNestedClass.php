<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

class ClassWithNestedClass
{
    public function __construct(
        public Person $person = new Person(),
        public string $nonNestedProperty = 'nonNestedProperty',
    ) {}
}
