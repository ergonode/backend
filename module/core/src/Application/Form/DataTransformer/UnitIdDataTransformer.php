<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Form\DataTransformer;

use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class UnitIdDataTransformer implements DataTransformerInterface
{
    /**
     * @param UnitId|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof UnitId) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Unit Id object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return UnitId|null
     */
    public function reverseTransform($value): ?UnitId
    {
        if ($value) {
            try {
                return new UnitId($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Unit Id "%s" value', $value));
            }
        }

        return null;
    }
}
