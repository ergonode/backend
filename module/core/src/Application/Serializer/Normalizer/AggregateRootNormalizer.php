<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer\Normalizer;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class AggregateRootNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
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
        unset($root['events']);

        return $root;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof AbstractAggregateRoot
            && !isset($context[$this->getContextKey($data)]);
    }

    private function getContextKey(AbstractAggregateRoot $root): string
    {
        return 'aggregate_root_normalization_'.spl_object_hash($root);
    }
}
