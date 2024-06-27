<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

use Vuryss\Serializer\Attribute\SerializerContext;

class Person
{
    public string $firstName = 'John';
    public string $lastName = 'Doe';
    public int $age = 25;
    public bool $isStudent = true;

    private string $unUsableProperty = 'unUsableProperty';

    #[SerializerContext(ignore: true)]
    public string $ignoredProperty = 'ignoredProperty';
}
