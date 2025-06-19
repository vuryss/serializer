# Upgrade from v1 to v2

- Serializer Interface is now fully compatible with Symfony Serializer Interface. Those can be used interchangeably.
This changes the signature of the `serialize` and `deserialize` methods to be fully compatible with Symfony Serializer.

- Attributes is changed to context on all places to make sure it's same as Symfony Serializer.

- As a result of serializer interface compatibility - the type is now required on both serialization and deserialization.
Only json type is supported as before.

- All exception now extend ExceptionInterface, to be similar to Symfony Serializer.

- Builtin context options keys are now constants in Context class instead of constant in SerializerInterface.
