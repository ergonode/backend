<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Form\Transformer;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class CategoryTreeIdDataTransformer implements DataTransformerInterface
{
    /**
     * @param CategoryTreeId|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof CategoryTreeId) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Category Tree Id object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return CategoryTreeId|null
     */
    public function reverseTransform($value): ?CategoryTreeId
    {
        if ($value) {
            try {
                return new CategoryTreeId($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Category Tree Id "%s" value', $value));
            }
        }

        return null;
    }
}
