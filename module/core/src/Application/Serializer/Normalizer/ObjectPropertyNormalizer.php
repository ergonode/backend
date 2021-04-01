<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer\Normalizer;

use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

/**
 * Denormalizes objects using properties reflections access.
 */
class ObjectPropertyNormalizer extends PropertyNormalizer
{
    /**
     * @var string[]|null[]
     */
    private array $discriminatorCache = [];
    private \ReflectionMethod $constructor;

    public function __construct(
        ClassDiscriminatorResolverInterface $classDiscriminatorResolver,
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null,
        PropertyTypeExtractorInterface $propertyTypeExtractor = null,
        callable $objectClassResolver = null,
        array $defaultContext = []
    ) {
        parent::__construct(
            $classMetadataFactory,
            $nameConverter,
            $propertyTypeExtractor,
            $classDiscriminatorResolver,
            $objectClassResolver,
            $defaultContext,
        );

        $this->constructor = new \ReflectionMethod($this, 'getConstructor');
    }

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
    public function supportsNormalization($data, $format = null): bool
    {
        return is_object($data) && !$data instanceof \Traversable;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return class_exists($type)
            || (interface_exists($type, false) && $this->classDiscriminatorResolver->getMappingForClass($type));
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
        return $this->constructor;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeValue($object, $attribute, $format = null, array $context = [])
    {
        $cacheKey = get_class($object);
        if (!array_key_exists($cacheKey, $this->discriminatorCache)) {
            $this->discriminatorCache[$cacheKey] = null;
            $mapping = $this->classDiscriminatorResolver->getMappingForMappedObject($object);
            $this->discriminatorCache[$cacheKey] = null === $mapping ? null : $mapping->getTypeProperty();
        }

        return $attribute === $this->discriminatorCache[$cacheKey] ?
            $this->classDiscriminatorResolver->getTypeForMappedObject($object) :
            parent::getAttributeValue($object, $attribute, $format, $context);
    }
}
