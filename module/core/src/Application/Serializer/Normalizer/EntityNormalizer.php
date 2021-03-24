<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Ergonode\EventSourcing\Domain\AbstractEntity;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EntityNormalizer implements
    CacheableSupportsMethodInterface,
    DenormalizerInterface,
    NormalizerInterface
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
        if (!$object instanceof AbstractEntity) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s"',
                AbstractEntity::class,
            ));
        }
        $clone = clone $object;
        $root = new \ReflectionProperty($clone, 'aggregateRoot');
        $root->setAccessible(true);
        $root->setValue($clone, null);

        $entity = $this->normalizer->normalize($clone, $format, $context);
        unset($entity['aggregateRoot']);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof AbstractEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (!is_subclass_of($type, AbstractEntity::class)) {
            throw new NotNormalizableValueException('Only AbstractEntity type supported.');
        }
        if (!is_array($data)) {
            throw new NotNormalizableValueException('Data is expected to be an array.');
        }
        unset($data['aggregateRoot']);

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_subclass_of($type, AbstractEntity::class);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }
}
