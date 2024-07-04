<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Exception\NormalizerNotFoundException;
use Vuryss\Serializer\Normalizer\ObjectNormalizer;

final class Normalizer
{
    private ObjectNormalizer $objectNormalizer;

    /**
     * @var array<class-string, NormalizerInterface>
     */
    private array $classSpecificNormalizers = [];

    /**
     * @param array<NormalizerInterface> $normalizers
     * @param array<string, scalar|string[]> $attributes
     */
    public function __construct(
        readonly array $normalizers,
        private readonly MetadataExtractorInterface $metadataExtractor,
        private readonly array $attributes = [],
    ) {
        $this->objectNormalizer = new ObjectNormalizer();

        foreach ($this->normalizers as $normalizer) {
            foreach ($normalizer->getSupportedClassNames() as $className) {
                $this->classSpecificNormalizers[$className] = $normalizer;
            }
        }
    }

    /**
     * @param array<string, scalar|string[]> $attributes
     *
     * @throws SerializerException
     */
    public function normalize(mixed $data, array $attributes): mixed
    {
        if (null === $data || is_scalar($data)) {
            return $data;
        }

        $attributes = $attributes + $this->attributes;

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->normalize($value, $attributes);
            }

            return $data;
        }

        if (!is_object($data)) {
            throw new NormalizerNotFoundException('Cannot serialize data of type: ' . gettype($data));
        }

        if (array_key_exists($data::class, $this->classSpecificNormalizers)) {
            return $this->classSpecificNormalizers[$data::class]->normalize($data, $this, $attributes);
        }

        if ($data instanceof \BackedEnum) {
            return $data->value;
        }

        return $this->objectNormalizer->normalize($data, $this, $attributes);
    }

    public function getMetadataExtractor(): MetadataExtractorInterface
    {
        return $this->metadataExtractor;
    }
}
