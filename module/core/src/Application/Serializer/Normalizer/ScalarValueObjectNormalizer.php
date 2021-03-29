<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizes scalar value objects by concept. To be supported class need to match the signature
 * class ScalarValueObjectClass
 * {
 *      public function __construct({string|float|int} $value)
 *      {}
 *
 *      public static function isValid({string|float|int} $value): bool
 *      {}
 *
 *      public function getValue(): {string|float|int}
 *      {}
 * }
 */
class ScalarValueObjectNormalizer implements
    NormalizerInterface,
    DenormalizerInterface,
    CacheableSupportsMethodInterface
{
    private const SCALAR_TYPES = [
        'string',
        'float',
        'int',
    ];

    /**
     * @var string[]|bool[]
     */
    private array $typeCache = [];

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = []): object
    {
        $valueType = null;
        if (!$this->isScalarValueObjectClass($type, $valueType)) {
            throw new NotNormalizableValueException("$type denormalization unsupported.");
        }
        $method = "is_$valueType";
        if (!$method($data)) {
            throw new NotNormalizableValueException(sprintf(
                'data is expected to be %s. %s given',
                $valueType,
                get_debug_type($data),
            ));
        }
        if (!$type::isValid($data)) {
            throw new NotNormalizableValueException("$data is not valid data for $type");
        }

        return new $type($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $this->isScalarValueObjectClass($type, $val);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $valueType = null;
        if (!is_object($object)
            || !$this->isScalarValueObjectClass(get_class($object), $valueType)
        ) {
            throw new InvalidArgumentException("Cannot normalize non-scalar object.");
        }

        return $object->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return is_object($data) && $this->isScalarValueObjectClass(get_class($data), $val);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }

    /**
     * @param mixed|null $valueType
     */
    private function isScalarValueObjectClass(string $type, &$valueType = null): bool
    {
        if (isset($this->typeCache[$type])) {
            $valueType = $this->typeCache[$type];

            return (bool) $this->typeCache[$type];
        }

        $resolved = $this->resolveIsScalarValueObjectClass($type, $valueType);
        if (!$resolved) {
            return $this->typeCache[$type] = false;
        }

        $this->typeCache[$type] = $valueType;

        return true;
    }

    /**
     * @param mixed|null $valueType
     */
    private function resolveIsScalarValueObjectClass(string $type, &$valueType = null): bool
    {
        if (!class_exists($type)
            || !method_exists($type, '__construct')
            || !method_exists($type, 'isValid')
            || !method_exists($type, 'getValue')
        ) {
            return false;
        }
        $reflectionClass = new \ReflectionClass($type);
        if ($reflectionClass->isAbstract()) {
            return false;
        }
        $valueType = $this->getSingleScalarParameter($reflectionClass->getConstructor());
        if (null === $valueType) {
            return false;
        }

        return
            $this->methodSignatureMatches($reflectionClass->getMethod('isValid'), $valueType, 'bool', true)
            && $this->methodSignatureMatches($reflectionClass->getMethod('getValue'), null, $valueType);
    }

    private function methodSignatureMatches(
        \ReflectionMethod $reflectionMethod,
        ?string $requiredParameterType = null,
        ?string $returnType = null,
        bool $isStatic = false
    ): bool {
        if ($returnType
            && (
                !$reflectionMethod->getReturnType() instanceof \ReflectionNamedType
                || $returnType !== $reflectionMethod->getReturnType()->getName()
            )
        ) {
            return false;
        }
        if ($isStatic !== $reflectionMethod->isStatic()) {
            return false;
        }
        if (!$requiredParameterType) {
            if (!$returnType) {
                throw new LogicException('At least one type has to be provided.');
            }

            return true;
        }

        return $requiredParameterType === $this->getSingleScalarParameter($reflectionMethod);
    }

    private function getSingleScalarParameter(
        \ReflectionMethod $reflectionMethod
    ): ?string {
        if ($reflectionMethod->isPublic()
            && 1 === $reflectionMethod->getNumberOfRequiredParameters()
            && $reflectionMethod->getParameters()[0]->getType()
            && $reflectionMethod->getParameters()[0]->getType() instanceof \ReflectionNamedType
            && in_array($reflectionMethod->getParameters()[0]->getType()->getName(), self::SCALAR_TYPES)
        ) {
            return $reflectionMethod->getParameters()[0]->getType()->getName();
        }

        return null;
    }
}
