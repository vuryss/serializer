# Fast serialization library

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

Supports PSR-6 CacheItemPoolInterface: https://www.php-fig.org/psr/psr-6/#cacheitempoolinterface

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
