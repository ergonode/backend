<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class AttributeIdArrayNormalizer implements
    ContextAwareDenormalizerInterface,
    DenormalizerAwareInterface,
    CacheableSupportsMethodInterface
{
    use DenormalizerAwareTrait;

    /**
     * @var ContextAwareDenormalizerInterface;
     */
    protected ContextAwareDenormalizerInterface $denormalizer;

    /**
     * {@inheritdoc}
     *
     * @throws NotNormalizableValueException
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (null === $this->denormalizer) {
            throw new BadMethodCallException('Please set a denormalizer before calling denormalize()!');
        }
        if (!\is_array($data)) {
            throw new InvalidArgumentException(sprintf('Expected type array, %s given.', \gettype($data)));
        }
        if ('[]' !== substr($type, -2)) {
            throw new InvalidArgumentException(sprintf('Unsupported class: %s', $type));
        }

        $denormalizer = $this->denormalizer;
        $type = substr($type, 0, -2);

        foreach ($data as $key => $value) {
            $data[$key] = $denormalizer->denormalize($value, $type, $format, $context);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        if (null === $this->denormalizer) {
            throw new BadMethodCallException(
                sprintf('The denormalizer needs to be set to allow "%s()" to be used.', __METHOD__)
            );
        }

        return AttributeId::class.'[]' === $type
            && $this->denormalizer->supportsDenormalization($data, substr($type, 0, -2), $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return $this->denormalizer instanceof CacheableSupportsMethodInterface
            && $this->denormalizer->hasCacheableSupportsMethod();
    }
}
