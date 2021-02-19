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
}
