<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Ergonode\EventSourcing\Domain\AbstractEntity;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class AbstractEntityNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if (!$object instanceof AbstractEntity) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s"',
                AbstractEntity::class,
            ));
        }
        $context[$this->getContextKey($object)] = true;
        $context[AbstractNormalizer::IGNORED_ATTRIBUTES][] = 'aggregateRoot';

        return $this->normalizer->normalize($object, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof AbstractEntity
            && !isset($context[$this->getContextKey($data)]);
    }

    private function getContextKey(AbstractEntity $root): string
    {
        return 'abstract_entity_normalization_'.spl_object_hash($root);
    }
}
