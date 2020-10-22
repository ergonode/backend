<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Form\Transformer;

use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductCollectionCodeTypeDataTransformer implements DataTransformerInterface
{
    /**
     * @param ProductCollectionTypeCode|null $value
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof ProductCollectionTypeCode) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Product Collection Type object');
        }

        return null;
    }

    /**
     * @param string|null $value
     */
    public function reverseTransform($value): ?ProductCollectionTypeCode
    {
        if ($value) {
            try {
                return new ProductCollectionTypeCode($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid "%s" value', $value));
            }
        }

        return null;
    }
}
