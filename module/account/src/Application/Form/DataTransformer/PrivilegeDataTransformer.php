<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\DataTransformer;

use Ergonode\Account\Domain\ValueObject\Privilege;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PrivilegeDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof Privilege) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Privilege object');
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $value
     */
    public function reverseTransform($value): ?Privilege
    {
        if ($value) {
            try {
                return new Privilege($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Privilege "%s" value', $value));
            }
        }

        return null;
    }
}
