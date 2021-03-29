<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Application\Serializer\Normalizer;

use Ergonode\Completeness\Domain\ReadModel\CompletenessReadModel;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CompletenessReadModelNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private AbstractObjectNormalizer $normalizer;

    public function __construct(AbstractObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if (!$object instanceof CompletenessReadModel) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s"',
                CompletenessReadModel::class,
            ));
        }

        $data = $this->normalizer->normalize($object, $format, $context);
        $data['percent'] = $object->getPercent();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof CompletenessReadModel;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }
}
