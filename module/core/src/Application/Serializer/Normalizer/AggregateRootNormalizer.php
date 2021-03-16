<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer\Normalizer;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

/**
 * As of Symfony 5.2 this normalizer can be replaced with appropriate `ignore` configuration.
 */
class AggregateRootNormalizer implements
    ContextAwareNormalizerInterface,
    NormalizerAwareInterface,
    CacheableSupportsMethodInterface
{
    use NormalizerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if (!$object instanceof AbstractAggregateRoot) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s"',
                AbstractAggregateRoot::class,
            ));
        }
        $context[$this->getContextKey($object)] = true;
        $root = $this->normalizer->normalize($object, $format, $context);
        if (isset($root['events'])) {
            unset($root['events']);
        }

        return $root;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof AbstractAggregateRoot
            && !isset($context[$this->getContextKey($data)]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }

    private function getContextKey(AbstractAggregateRoot $root): string
    {
        return 'aggregate_root_normalization_'.spl_object_hash($root);
    }
}
