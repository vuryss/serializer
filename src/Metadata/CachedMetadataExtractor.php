<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

use Vuryss\Serializer\MetadataExtractorInterface;
use Vuryss\Serializer\SerializerException;

class CachedMetadataExtractor implements MetadataExtractorInterface
{
    /**
     * @var array<string, ClassMetadata>
     */
    private array $cache = [];

    public function __construct(
        private readonly MetadataExtractorInterface $metadataExtractor,
    ) {}

    /**
     * @param class-string $class
     * @throws SerializerException
     */
    public function extractClassMetadata(string $class): ClassMetadata
    {
        if (!isset($this->cache[$class])) {
            $this->cache[$class] = $this->metadataExtractor->extractClassMetadata($class);
        }

        return $this->cache[$class];
    }
}
