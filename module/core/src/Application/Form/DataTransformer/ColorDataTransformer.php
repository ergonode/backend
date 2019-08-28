<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Form\DataTransformer;

use Ergonode\Core\Domain\ValueObject\Color;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class ColorDataTransformer implements DataTransformerInterface
{
    /**
     * @param Color|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof Color) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Color object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return Color|null
     */
    public function reverseTransform($value): ?Color
    {
        if ($value) {
            try {
                return new Color($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Color "%s" value', $value));
            }
        }

        return null;
    }
}
