<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AssocArrayDenormalizer implements
    CacheableSupportsMethodInterface,
    DenormalizerInterface,
    DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    /**
     * @var string[]
     */
    private array $supportedTypes;

    public function __construct(array $supportedTypes)
    {
        $this->supportedTypes = [];
        foreach ($supportedTypes as $supportedType) {
            $this->supportedTypes[trim($supportedType, '\\')] = true;
        }
    }

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
        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf('Expected type array, %s given.', get_debug_type($data)));
        }
        $elementType = $this->getElementType($type);
        if (!$elementType) {
            throw new InvalidArgumentException(sprintf('Unsupported type: %s', $type));
        }

        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = $this->denormalizer->denormalize($value, $elementType, $format, $context);
        }

        return $result;
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
        $elementType = $this->getElementType($type);

        return $elementType
            && isset($this->supportedTypes[$elementType])
            && $this->denormalizer->supportsDenormalization($data, $elementType, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }

    private function getElementType(string $type): ?string
    {
        if ('[]' !== substr($type, -2)) {
            return null;
        }

        return trim(substr($type, 0, -2), '\\');
    }
}
