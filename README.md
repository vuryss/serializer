# Fast serialization library

Serializes and deserializes complex data structures to and from json.
Ideas taken from Symfony's Serializer component, Serde and others.

Symfony serializer was very flexible, but also very slow. This library tries to be as fast as possible.

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
