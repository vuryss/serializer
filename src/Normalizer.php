<?php

declare(strict_types=1);

namespace Vuryss\Serializer;

use Vuryss\Serializer\Exception\NormalizerNotFoundException;
use Vuryss\Serializer\Normalizer\NormalizerInterface;

final readonly class Normalizer
{
    /**
     * @param array<NormalizerInterface> $normalizers
     * @param array<string, mixed> $context Options normalizers/encoders have access to
     */
    public function __construct(
        private array $normalizers,
        private MetadataExtractorInterface $metadataExtractor,
        private array $context = [],
    ) {}

    /**
     * @param array<string, mixed> $context Options normalizers/encoders have access to
     *
     * @phpstan-return array<mixed>|string|int|float|bool|null
     * @throws ExceptionInterface
     */
    public function normalize(mixed $data, array $context): array|string|int|float|bool|null
    {
        if ($data instanceof \JsonSerializable) {
            // @phpstan-ignore-next-line (We cannot fix PHP interface)
            return $data->jsonSerialize();
        }

        $normalizer = $this->resolveNormalizer($data);

        $context = $context + $this->context;

        return $normalizer->normalize($data, $this, $context);
    }

    public function getMetadataExtractor(): MetadataExtractorInterface
    {
        return $this->metadataExtractor;
    }

    /**
     * @throws NormalizerNotFoundException
     */
    private function resolveNormalizer(mixed $data): NormalizerInterface
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supportsNormalization($data)) {
                return $normalizer;
            }
        }

        throw new NormalizerNotFoundException(
            sprintf('No normalizer found for the given data: %s', get_debug_type($data)),
        );
    }
}
