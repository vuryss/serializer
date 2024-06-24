<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

use Vuryss\Serializer\Metadata\CachedMetadataExtractor;
use Vuryss\Serializer\Metadata\MetadataExtractor;
use Vuryss\Serializer\Tests\Datasets\Person;

test('Saves to PSR-6 cache during serialization', function () {
    $psr6Cache = Mockery::mock(\Psr\Cache\CacheItemPoolInterface::class);
    $cacheItem = Mockery::mock(\Psr\Cache\CacheItemInterface::class);

    $psr6Cache
        ->shouldReceive('getItem')
        ->with(base64_encode(Person::class))
        ->twice()
        ->andReturn($cacheItem);

    $cacheItem
        ->shouldReceive('isHit')
        ->once()
        ->andReturn(false);

    $cacheItem
        ->shouldReceive('set')
        ->once()
        ->with(Mockery::type(\Vuryss\Serializer\Metadata\ClassMetadata::class));

    $psr6Cache
        ->shouldReceive('saveDeferred')
        ->once()
        ->with($cacheItem);

    $serializer = new \Vuryss\Serializer\Serializer(
        metadataExtractor: new CachedMetadataExtractor(
            metadataExtractor: new MetadataExtractor(),
            externalCache: $psr6Cache,
        )
    );

    $person = new Person();
    $person->firstName = 'John';
    $person->lastName = 'Doe';
    $person->age = 25;
    $person->isStudent = true;

    $serializer->serialize($person);
});

test('Reads from PSR-6 cache', function () {
    $classMetadata = (new MetadataExtractor())->extractClassMetadata(Person::class);

    $psr6Cache = Mockery::mock(\Psr\Cache\CacheItemPoolInterface::class);
    $cacheItem = Mockery::mock(\Psr\Cache\CacheItemInterface::class);

    $psr6Cache
        ->shouldReceive('getItem')
        ->with(base64_encode(Person::class))
        ->once()
        ->andReturn($cacheItem);

    $cacheItem
        ->shouldReceive('isHit')
        ->once()
        ->andReturn(true);

    $cacheItem
        ->shouldReceive('get')
        ->once()
        ->andReturn($classMetadata);

    $serializer = new \Vuryss\Serializer\Serializer(
        metadataExtractor: new CachedMetadataExtractor(
            metadataExtractor: new MetadataExtractor(),
            externalCache: $psr6Cache,
        )
    );

    $person = new Person();
    $person->firstName = 'John';
    $person->lastName = 'Doe';
    $person->age = 25;
    $person->isStudent = true;

    $serializer->serialize($person);
});

test('Cache reading exception does not break serializer', function () {
    $psr6Cache = Mockery::mock(\Psr\Cache\CacheItemPoolInterface::class);

    $psr6Cache
        ->shouldReceive('getItem')
        ->with(base64_encode(Person::class))
        ->twice()
        ->andThrow(new Exception());

    $serializer = new \Vuryss\Serializer\Serializer(
        metadataExtractor: new CachedMetadataExtractor(
            metadataExtractor: new MetadataExtractor(),
            externalCache: $psr6Cache,
        )
    );

    $person = new Person();
    $person->firstName = 'John';
    $person->lastName = 'Doe';
    $person->age = 25;
    $person->isStudent = true;

    $serializer->serialize($person);
});
