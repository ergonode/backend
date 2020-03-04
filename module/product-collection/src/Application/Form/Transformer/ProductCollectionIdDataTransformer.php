<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Form\Transformer;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class ProductCollectionIdDataTransformer implements DataTransformerInterface
{
    /**
     * @param ProductCollectionId|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof ProductCollectionId) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Product Collection Id object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return ProductCollectionId|null
     */
    public function reverseTransform($value): ?ProductCollectionId
    {
        if ($value) {
            try {
                return new ProductCollectionId($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid "%s" value', $value));
            }
        }

        return null;
    }
}
