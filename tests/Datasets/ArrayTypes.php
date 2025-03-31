<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

class ArrayTypes
{
    /**
     * @var Person[]
     */
    public array $type1;

    /**
     * @var array<Person>
     */
    public array $type2;

    /**
     * @var array<int, Person>
     */
    public array $type3;

    /**
     * @var list<Person>
     */
    public array $type4;

    /**
     * @var iterable<Person>
     */
    public array $type5;

    /**
     * @var array<string, Person>
     */
    public array $type6;
}
