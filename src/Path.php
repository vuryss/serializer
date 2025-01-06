<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

class Path
{
    /**
     * @var array<string>
     */
    private array $segments = [];

    public function toString(): string
    {
        $path = '$';

        foreach ($this->segments as $segment) {
            $path .= str_starts_with($segment, '[') ? $segment : '.' . $segment;
        }

        return $path;
    }

    public function pushArrayKey(int|string $key): void
    {
        $this->segments[] = '[' . $key . ']';
    }

    public function pop(): void
    {
        array_pop($this->segments);
    }

    public function pushObjectProperty(string $serializedName): void
    {
        $this->segments[] = $serializedName;
    }
}
