<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Form\Transformer;

use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductCollectionCodeDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof ProductCollectionCode) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Product Collection Code object');
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $value
     */
    public function reverseTransform($value): ?ProductCollectionCode
    {
        if ($value) {
            try {
                return new ProductCollectionCode($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid "%s" value', $value));
            }
        }

        return null;
    }
}
