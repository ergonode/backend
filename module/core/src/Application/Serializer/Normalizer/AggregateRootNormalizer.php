<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer\Normalizer;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * As of Symfony 5.2 this normalizer can be replaced with appropriate `ignore` configuration.
 */
class AggregateRootNormalizer implements
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
        if (!$object instanceof AbstractAggregateRoot) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s"',
                AbstractAggregateRoot::class,
            ));
        }
        $clone = clone $object;
        $events = new \ReflectionProperty($clone, 'events');
        $events->setAccessible(true);
        $events->setValue($clone, []);

        $root = $this->normalizer->normalize($clone, $format, $context);

        unset($root['events']);
        unset($root['sequence']);

        return $root;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof AbstractAggregateRoot;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = []): AbstractAggregateRoot
    {
        if (!is_subclass_of($type, AbstractAggregateRoot::class)) {
            throw new NotNormalizableValueException('Only AbstractAggregateRoot type supported.');
        }
        if (!is_array($data)) {
            throw new NotNormalizableValueException('Data is expected to be an array.');
        }
        unset($data['events']);
        unset($data['sequence']);

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_subclass_of($type, AbstractAggregateRoot::class);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }
}
