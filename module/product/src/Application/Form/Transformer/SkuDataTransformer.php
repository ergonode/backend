<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Form\Transformer;

use Ergonode\Product\Domain\ValueObject\Sku;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class SkuDataTransformer implements DataTransformerInterface
{
    /**
     * @param Sku|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof Sku) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Sku object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return Sku|null
     */
    public function reverseTransform($value): ?Sku
    {
        if ($value) {
            try {
                return new Sku($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Sku "%s" value', $value));
            }
        }

        return null;
    }
}
