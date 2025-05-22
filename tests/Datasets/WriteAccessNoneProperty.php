<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

class WriteAccessNoneProperty
{
    public readonly string $readOnlyProperty;
    public string $constructorProperty;

    public function __construct(string $constructorProperty, string $readOnlyValue = 'initialValue')
    {
        $this->constructorProperty = $constructorProperty;
        $this->readOnlyProperty = $readOnlyValue;
    }
}
