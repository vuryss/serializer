<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

use Psr\Cache\CacheItemPoolInterface;
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
        private readonly ?CacheItemPoolInterface $externalCache = null,
    ) {}

    /**
     * @param class-string $class
     * @throws SerializerException
     */
    public function extractClassMetadata(string $class): ClassMetadata
    {
        if (!isset($this->cache[$class])) {
            $cacheKey = base64_encode($class);

            if (null !== $this->externalCache) {
                try {
                    $cacheItem = $this->externalCache->getItem($cacheKey);

                    if ($cacheItem->isHit()) {
                        $this->cache[$class] = $cacheItem->get();

                        return $this->cache[$class];
                    }
                } catch (\Throwable) {
                }
            }

            $this->cache[$class] = $this->metadataExtractor->extractClassMetadata($class);

            if (null !== $this->externalCache) {
                try {
                    $cacheItem = $this->externalCache->getItem($cacheKey);
                    $cacheItem->set($this->cache[$class]);
                    $this->externalCache->saveDeferred($cacheItem);
                } catch (\Throwable) {
                }
            }
        }

        return $this->cache[$class];
    }
}
