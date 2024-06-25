# Fast serialization library

[![Tests](https://github.com/vuryss/serializer/workflows/Tests/badge.svg)](https://github.com/vuryss/serializer/actions?query=workflow:"Tests")
[![codecov](https://codecov.io/gh/vuryss/serializer/graph/badge.svg?token=kK0ZHh3raA)](https://codecov.io/gh/vuryss/serializer)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/760e3d4f985248fd8bb47b947873b847)](https://app.codacy.com/gh/vuryss/serializer/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)
[![CodeFactor](https://www.codefactor.io/repository/github/vuryss/serializer/badge)](https://www.codefactor.io/repository/github/vuryss/serializer)
![GitHub Release](https://img.shields.io/github/v/release/vuryss/serializer)
[![License](http://poser.pugx.org/vuryss/serializer/license)](https://packagist.org/packages/vuryss/serializer)
[![PHP Version Require](http://poser.pugx.org/vuryss/serializer/require/php)](https://packagist.org/packages/vuryss/serializer)

Serializes and deserializes complex data structures to and from json.
Ideas taken from Symfony's Serializer component, Serde and others.

Symfony serializer was very flexible, but also very slow. This library tries to be as fast as possible.

## Features

### Serialization

- Properties are serialized if they are either public or have a getter method.

```php
$person = new Person();
$person->firstName = 'Maria';
$person->lastName = 'Valentina';
$person->age = 36;
$person->isStudent = false;

$serializer = new Serializer();
$json = $serializer->serialize($person);
// {"firstName":"Maria","lastName":"Valentina","age":36,"isStudent":false}
```

### Deserialization

- Properties are deserialized if they are public, instantiable in public constructor or have a setter method.

```php
$json = '{"firstName":"Maria","lastName":"Valentina","age":36,"isStudent":false}';
$serializer = new Serializer();
$person = $serializer->deserialize($json, Person::class);
```

### Caching - optional, but highly recommended, otherwise the library will be slow

Supports PSR-6 CacheItemPoolInterface: <https://www.php-fig.org/psr/psr-6/#cacheitempoolinterface>

No need to chain in-memory cache with external cache, the library will do it for you.
Cache will be called once per used class (used in serialization or deserialization), then will be cached in memory until the script ends. 

```php
$pst6cache = new CacheItemPool(); // Some PSR-6 cache implementation
$serializer = new Serializer(
    metadataExtractor: new CachedMetadataExtractor(
        new MetadataExtractor(),
        $pst6cache,
    ),
);
```

### Custom object property serialized name

```php
class SomeClass
{
    #[SerializerContext(name: 'changedPropertyName')]
    public string $someProperty;
}
```

### Serialization groups

```php
class SomeClass
{
    #[SerializerContext(groups: ['group1'])]
    public string $property1;

    // Has implicit group 'default'
    public string $property2;
}

    
$serializer = new Serializer();
$object = new SomeClass();
$object->property1 = 'value1';
$object->property2 = 'value2';
$serializer->serialize($object, attributes: [SerializerInterface::ATTRIBUTE_GROUPS => ['group1']]); // {"property1":"value1"}
```

### Deserialization groups

```php
class SomeClass
{
    #[SerializerContext(groups: ['group1'])]
    public string $property1;

    // Has implicit group 'default'
    public string $property2;
}

    
$serializer = new Serializer();
$data = '{"property1":"value1","property2":"value2"}';
$object = $serializer->deserialize($data, SomeClass::class, attributes: [SerializerInterface::ATTRIBUTE_GROUPS => ['group1']]);
isset($object->property1); // true
isset($object->property2); // false
```

### Custom date format

Per property:
```php
class SomeClass
{
    #[SerializerContext(attributes: [SerializerInterface::ATTRIBUTE_DATETIME_FORMAT => 'Y-m-d'])]
    public DateTime $someDate;
}
```

Or globally:
```php
$serializer = new Serializer(
    attributes: [
        SerializerInterface::ATTRIBUTE_DATETIME_FORMAT => \DateTimeInterface::RFC2822,
    ]
);
```

### Handling of NULL values

- By default, NULL values are included in the serialized value.

To disable this you can use the `SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES` attribute:

Per property:
```php
class SomeClass
{
    #[SerializerContext(attributes: [SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES => true])]
    public ?string $someProperty;
}
```

Or globally:
```php
$serializer = new Serializer(
    attributes: [
        SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES => true,
    ]
);
```

## Build, run & test locally

To enter the prepared container environment:

```bash
docker-compose up -d
docker-compose exec library bash
```

Install package dependencies:

```bash
composer install -o
```

Run tests:

```bash
composer test
```
