<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\DataTransformer;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class RoleIdDataTransformer implements DataTransformerInterface
{
    /**
     * @param RoleId|null $value
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof RoleId) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid RoleId object');
        }

        return null;
    }

    /**
     * @param string|null $value
     */
    public function reverseTransform($value): ?RoleId
    {
        if ($value) {
            try {
                return new RoleId($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid "%s" value', $value));
            }
        }

        return null;
    }
}
