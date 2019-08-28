<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Transformer;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class AttributeCodeDataTransformer
 */
class AttributeCodeDataTransformer implements DataTransformerInterface
{
    /**
     * @param AttributeCode|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof AttributeCode) {
                return $value->getValue();
            }

            throw new TransformationFailedException('Invalid AttributeCode object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return AttributeCode|null
     */
    public function reverseTransform($value): ?AttributeCode
    {
        if ($value) {
            try {
                return new AttributeCode($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('invalid attribute code %s value', $value));
            }
        }

        return null;
    }
}
