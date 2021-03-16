<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

/**
 * Denormalizes objects using properties reflections access.
 */
class ObjectPropertyNormalizer extends PropertyNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return parent::supportsDenormalization($data, $type, $format)
            || $this->isMappedClass($type);
    }

    /**
     * Fakes non-public constructor - allows to always instantiate object not using a constructor.
     * @see \Symfony\Component\Serializer\Normalizer\AbstractNormalizer::instantiateObject
     *
     * {@inheritdoc}
     */
    protected function getConstructor(
        array &$data,
        $class,
        array &$context,
        \ReflectionClass $reflectionClass,
        $allowedAttributes
    ) {
        return (new \ReflectionMethod($this, 'getConstructor'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeValue($object, $attribute, $format = null, array $context = [])
    {
        $mapped = $this->classDiscriminatorResolver->getMappingForMappedObject($object);
        if (!$mapped || $attribute !== $mapped->getTypeProperty()) {
            return parent::getAttributeValue($object, $attribute, $format, $context);
        }

        return $this->classDiscriminatorResolver->getTypeForMappedObject($object);
    }

    private function isMappedClass(string $type): bool
    {
        return (interface_exists($type) || class_exists($type))
            && $this->classDiscriminatorResolver->getMappingForClass($type);
    }
}
