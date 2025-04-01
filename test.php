<?php

declare(strict_types=1);

class Test {
    public string|int $property;
}

$reflectionProperty = new ReflectionProperty(Test::class, 'property');
$unionTypes = $reflectionProperty->getType()->getTypes();

foreach ($unionTypes as $type) {
    echo $type->getName() . PHP_EOL;
}
