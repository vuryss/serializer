<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

interface MetadataExtractorInterface
{
    /**
     * @param class-string $class
     * @throws ExceptionInterface
     */
    public function extractClassMetadata(string $class): Metadata\ClassMetadata;
}
