<?php

declare(strict_types=1);

namespace Vuryss\Serializer\Normalizer;

use Vuryss\Serializer\Context;
use Vuryss\Serializer\Exception\InvalidAttributeUsageException;
use Vuryss\Serializer\Normalizer;
use Vuryss\Serializer\NormalizerInterface;

class DateTimeNormalizer implements NormalizerInterface
{
    public function normalize(mixed $data, Normalizer $normalizer, array $context): string
    {
        assert($data instanceof \DateTimeInterface);

        $format = $context[Context::DATETIME_FORMAT] ?? \DateTimeInterface::RFC3339;

        if (!is_string($format)) {
            throw new InvalidAttributeUsageException('DateTime format attribute must be a string');
        }

        return $data->format($format);
    }

    public function supportsNormalization(mixed $data): bool
    {
        return $data instanceof \DateTimeInterface;
    }
}
