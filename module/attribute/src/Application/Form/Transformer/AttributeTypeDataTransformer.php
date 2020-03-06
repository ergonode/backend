<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Transformer;

use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class AttributeTypeDataTransformer implements DataTransformerInterface
{
    /**
     * @param AttributeType|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof AttributeType) {
                return $value->getValue();
            }

            throw new TransformationFailedException('Invalid AttributeType object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return AttributeType|null
     */
    public function reverseTransform($value): ?AttributeType
    {
        if ($value) {
            try {
                return new AttributeType($value);
            } catch (\Throwable $e) {
                throw new TransformationFailedException('invalid attribute type value');
            }
        }

        return null;
    }
}
