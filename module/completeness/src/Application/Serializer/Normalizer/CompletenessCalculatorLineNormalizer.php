<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Application\Serializer\Normalizer;

use Ergonode\Completeness\Domain\Calculator\CompletenessCalculatorLine;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CompletenessCalculatorLineNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if (!$object instanceof CompletenessCalculatorLine) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s"',
                CompletenessCalculatorLine::class,
            ));
        }

        return [
            'id' => (string) $object->getAttributeId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CompletenessCalculatorLine;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }
}
