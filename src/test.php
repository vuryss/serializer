<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$serializer = new Vuryss\Serializer\Serializer();

$json = \Vuryss\Serializer\Tests\Datasets\Complex1\Car::getJsonSerialized();

$serializer->deserialize('["list","of","data",false,true,null,1,1.2]');
