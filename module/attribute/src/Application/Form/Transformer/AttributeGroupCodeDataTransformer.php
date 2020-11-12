<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form\Transformer;

use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AttributeGroupCodeDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof AttributeGroupCode) {
                return $value->getValue();
            }

            throw new TransformationFailedException('Invalid AttributeGroupCode object');
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $value
     */
    public function reverseTransform($value): ?AttributeGroupCode
    {
        if ($value) {
            try {
                return new AttributeGroupCode($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid attribute code %s value', $value));
            }
        }

        return null;
    }
}
