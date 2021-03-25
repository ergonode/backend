<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Serializer\Normalizer;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class AttributeNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

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
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof AbstractAttribute && !isset($context[$this->getContextKey($data)]);
    }

    private function getContextKey(AbstractAttribute $attribute): string
    {
        return 'attribute_normalization_'.spl_object_hash($attribute);
    }
}
