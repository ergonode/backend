<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Serializer\Normalizer;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\SystemAttributeCode;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class AttributeNormalizer implements
    ContextAwareNormalizerInterface,
    NormalizerAwareInterface,
    ContextAwareDenormalizerInterface,
    DenormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use DenormalizerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $context[$this->getContextKey($object)] = true;
        $attribute = $this->normalizer->normalize($object, $format, $context);
        $attribute['type'] = $object->getType();

        return $attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $context[$this->getDenormalizeContextKey()] = true;

        if (!is_array($data)) {
            throw new NotNormalizableValueException('data must be an array');
        }

        if (!is_string($data['code'] ?? null)) {
            throw new NotNormalizableValueException('Code key must be set and must be string');
        }
        $code = $data['code'];
        unset($data['code']);
        /** @var AbstractAttribute $attribute */
        $attribute = $this->denormalizer->denormalize($data, $type, $format, $context);

        try {
            $systemCode = $attribute->isSystem() ? new SystemAttributeCode($code) : new AttributeCode($code);
        } catch (\InvalidArgumentException $exception) {
            throw new NotNormalizableValueException($exception->getMessage());
        }

        $reflection = new \ReflectionProperty($attribute, 'code');
        $reflection->setAccessible(true);
        $reflection->setValue($attribute, $systemCode);

        return $attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof AbstractAttribute && !isset($context[$this->getContextKey($data)]);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return is_subclass_of($type, AbstractAttribute::class) && !isset($context[$this->getDenormalizeContextKey()]);
    }

    private function getContextKey(AbstractAttribute $attribute): string
    {
        return 'attribute_normalization_'.spl_object_hash($attribute);
    }

    private function getDenormalizeContextKey(): string
    {
        return 'attribute_denormalization';
    }
}
