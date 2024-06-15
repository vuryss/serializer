# Fast serialization library

Serializes and deserializes complex data structures to and from json.
Ideas taken from Symfony's Serializer component, Serde and others.

Symfony serializer was very flexible, but also very slow. This library tries to be as fast as possible.

## Features

### Serialization

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

```php
$json = '{"firstName":"Maria","lastName":"Valentina","age":36,"isStudent":false}';
$serializer = new Serializer();
$person = $serializer->deserialize($json, Person::class);
```

### Custom object property serialized name (works in both serialization and deserialization)
    
```php
class SomeClass
{
    #[SerializerContext(name: 'changedPropertyName')]
    public string $someProperty;
}
```

### Notes:
- Properties are serialized if they are either public or have a getter method.
- Properties are deserialized if they are public, instantiable in public constructor or have a setter method.

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
