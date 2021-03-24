<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class LanguageArrayNormalizer implements
    ContextAwareDenormalizerInterface,
    SerializerAwareInterface,
    CacheableSupportsMethodInterface
{
    /**
     * @var SerializerInterface|DenormalizerInterface
     */
    private $serializer;

    /**
     * {@inheritdoc}
     *
     * @throws NotNormalizableValueException
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (null === $this->serializer) {
            throw new BadMethodCallException('Please set a serializer before calling denormalize()!');
        }
        if (!\is_array($data)) {
            throw new InvalidArgumentException(sprintf('Expected type array, %s given.', \gettype($data)));
        }
        if ('[]' !== substr($type, -2)) {
            throw new InvalidArgumentException(sprintf('Unsupported class: %s', $type));
        }

        $serializer = $this->serializer;
        $type = substr($type, 0, -2);

        foreach ($data as $key => $value) {
            $data[$key] = $serializer->denormalize($value, $type, $format, $context);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        if (null === $this->serializer) {
            throw new BadMethodCallException(
                sprintf('The serializer needs to be set to allow "%s()" to be used.', __METHOD__)
            );
        }

        return Language::class.'[]' === $type
            && $this->serializer->supportsDenormalization($data, substr($type, 0, -2), $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        if (!$serializer instanceof DenormalizerInterface) {
            throw new InvalidArgumentException('Expected  implement DenormalizerInterface.');
        }

        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return $this->serializer instanceof CacheableSupportsMethodInterface
            && $this->serializer->hasCacheableSupportsMethod();
    }
}
