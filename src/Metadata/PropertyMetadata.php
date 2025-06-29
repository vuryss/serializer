<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Metadata;

class PropertyMetadata
{
    public ?string $getterMethod = null;
    public ?string $setterMethod = null;

    /**
     * @param DataType[] $types
     * @param string[] $groups
     * @param array<string, mixed> $context
     */
    public function __construct(
        public string $name,
        public string $serializedName,
        public array $types,
        public array $groups,
        public array $context,
        public ReadAccess $readAccess,
        public WriteAccess $writeAccess,
        public bool $ignore,
    ) {
        if (ReadAccess::GETTER == $this->readAccess) {
            $this->getterMethod = 'get' . ucfirst($this->name);
        }

        if (WriteAccess::SETTER == $this->writeAccess) {
            $this->setterMethod = 'set' . ucfirst($this->name);
        }
    }
}
