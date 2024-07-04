<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Denormalizer\ArrayDenormalizer;
use Vuryss\Serializer\Denormalizer\BasicTypesDenormalizer;
use Vuryss\Serializer\Denormalizer\EnumDenormalizer;
use Vuryss\Serializer\Denormalizer\InterfaceDenormalizer;
use Vuryss\Serializer\Denormalizer\ObjectDenormalizer;
use Vuryss\Serializer\Exception\DenormalizerNotFoundException;
use Vuryss\Serializer\Metadata\BuiltInType;
use Vuryss\Serializer\Metadata\DataType;

final class Denormalizer
{
    private readonly BasicTypesDenormalizer $basicTypesDenormalizer;
    private readonly ArrayDenormalizer $arrayDenormalizer;
    private readonly EnumDenormalizer $enumDenormalizer;
    private readonly ObjectDenormalizer $objectDenormalizer;
    private readonly InterfaceDenormalizer $interfaceDenormalizer;

    /**
     * @var array<DenormalizerInterface>
     */
    private array $classSpecificDenormalizers = [];

    /**
     * @param array<DenormalizerInterface> $denormalizers
     * @param array<string, scalar|string[]> $attributes
     */
    public function __construct(
        array $denormalizers,
        private readonly MetadataExtractorInterface $metadataExtractor,
        private readonly array $attributes = [],
    ) {
        $this->basicTypesDenormalizer = new BasicTypesDenormalizer();
        $this->arrayDenormalizer = new ArrayDenormalizer();
        $this->enumDenormalizer = new EnumDenormalizer();
        $this->objectDenormalizer = new ObjectDenormalizer();
        $this->interfaceDenormalizer = new InterfaceDenormalizer();

        foreach ($denormalizers as $denormalizer) {
            foreach ($denormalizer->getSupportedClassNames() as $className) {
                $this->classSpecificDenormalizers[$className] = $denormalizer;
            }
        }
    }

    /**
     * Denormalized data into the given type.
     *
     * @param array<string, scalar|string[]> $attributes
     *
     * @throws SerializerException
     */
    public function denormalize(mixed $data, DataType $dataType, Path $path, array $attributes): mixed
    {
        $dataType->attributes += $this->attributes;

        return match ($dataType->type) {
            BuiltInType::STRING,
            BuiltInType::INTEGER,
            BuiltInType::FLOAT,
            BuiltInType::BOOLEAN,
            BuiltInType::MIXED,
            BuiltInType::NULL => $this->basicTypesDenormalizer->denormalize($data, $dataType, $path),
            BuiltInType::ARRAY => $this->arrayDenormalizer->denormalize($data, $dataType, $this, $path, $attributes),
            BuiltInType::OBJECT,
            BuiltInType::ENUM,
            BuiltInType::INTERFACE => $this->denormalizeObject($data, $dataType, $path, $attributes),
            default => throw new DenormalizerNotFoundException(sprintf(
                'Could not denormalize data at path %s. Received data of type %s, expected type %s',
                $path->toString(),
                get_debug_type($data),
                $dataType->type->value,
            )),
        };
    }

    public function getMetadataExtractor(): MetadataExtractorInterface
    {
        return $this->metadataExtractor;
    }

    /**
     * @throws SerializerException
     */
    private function denormalizeObject(
        mixed $data,
        DataType $dataType,
        Path $path,
        array $attributes
    ): mixed {
        if (null !== $dataType->className && isset($this->classSpecificDenormalizers[$dataType->className])) {
            return $this->classSpecificDenormalizers[$dataType->className]->denormalize($data, $dataType, $this, $path, $attributes);
        }

        return match($dataType->type) {
            BuiltInType::ENUM => $this->enumDenormalizer->denormalize($data, $dataType, $path),
            BuiltInType::INTERFACE => $this->interfaceDenormalizer->denormalize($data, $dataType, $this, $path, $attributes),
            default => $this->objectDenormalizer->denormalize($data, $dataType, $this, $path, $attributes),
        };
    }
}
