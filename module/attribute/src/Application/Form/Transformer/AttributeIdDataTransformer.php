<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Transformer;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AttributeIdDataTransformer implements DataTransformerInterface
{
    /**
     * @param AttributeId|null $value
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof AttributeId) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Attribute Id object');
        }

        return null;
    }

    /**
     * @param string|null $value
     */
    public function reverseTransform($value): ?AttributeId
    {
        if ($value) {
            try {
                return new AttributeId($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid "%s" value', $value));
            }
        }

        return null;
    }
}
