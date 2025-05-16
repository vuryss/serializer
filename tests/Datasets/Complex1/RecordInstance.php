<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets\Complex1;

class RecordInstance
{
    /**
     * @param ServiceItem[]   $serviceItems
     * @param RecordTextBlock[] $textBlocks
     */
    public function __construct(
        public RecordType $recordType,
        public array $serviceItems,
        public array $textBlocks,
        public ?string $recordName = null,
    ) {}
}
