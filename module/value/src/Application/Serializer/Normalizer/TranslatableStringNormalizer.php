<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Application\Serializer\Normalizer;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TranslatableStringNormalizer implements
    NormalizerInterface,
    DenormalizerInterface,
    CacheableSupportsMethodInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if (!$object instanceof TranslatableString) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s"',
                TranslatableString::class,
            ));
        }

        return $object->getTranslations();
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = []): TranslatableString
    {
        if (!is_array($data)) {
            throw new NotNormalizableValueException('Cannot denormalize non-array data.');
        }
        try {
            return new TranslatableString($data);
        } catch (\InvalidArgumentException $exception) {
            throw new NotNormalizableValueException('Invalid translations data.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof TranslatableString;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return $type === TranslatableString::class;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }
}
