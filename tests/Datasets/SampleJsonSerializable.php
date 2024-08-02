<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Tests\Datasets;

readonly class SampleJsonSerializable implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return [
            'some-key' => 'some-value',
            'other-key' => 123,
            'nested' => [
                'nested-key' => 'nested-value',
            ],
        ];
    }
}
