<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Symfony\Component\Serializer\SerializerAwareTrait;

class LanguagePrivilegesArrayDenormalizer implements
    ContextAwareDenormalizerInterface,
    SerializerAwareInterface,
    CacheableSupportsMethodInterface
{
    use SerializerAwareTrait;

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

        $type = substr($type, 0, -2);

        foreach ($data as $key => $value) {
            /* @phpstan-ignore-next-line */
            $data[$key] = $this->serializer->denormalize($value, $type, $format, $context);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        if (null === $this->serializer) {
            throw new BadMethodCallException(sprintf(
                'The serializer needs to be set to allow "%s()" to be used.',
                __METHOD__
            ));
        }

        if (LanguagePrivileges::class.'[]' !== $type) {
            return false;
        }

        /* @phpstan-ignore-next-line */
        return $this->serializer->supportsDenormalization($data, substr($type, 0, -2), $format, $context);
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
