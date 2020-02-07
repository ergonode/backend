<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Form\Transformer;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class ProductCollectionTypeIdDataTransformer implements DataTransformerInterface
{
    /**
     * @param ProductCollectionTypeId|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof ProductCollectionTypeId) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Product Collection Type Id object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return ProductCollectionTypeId|null
     */
    public function reverseTransform($value): ?ProductCollectionTypeId
    {
        if ($value) {
            try {
                return new ProductCollectionTypeId($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid "%s" value', $value));
            }
        }

        return null;
    }
}
