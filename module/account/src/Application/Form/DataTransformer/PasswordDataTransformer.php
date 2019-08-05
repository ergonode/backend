<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\DataTransformer;

use Ergonode\Account\Domain\ValueObject\Password;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class PasswordDataTransformer implements DataTransformerInterface
{
    /**
     * @param Password|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        $result = null;

        if ($value instanceof Password) {
            $result = $value->getValue();
        }

        return $result;
    }

    /**
     * @param string|null $value
     *
     * @return Password|null
     */
    public function reverseTransform($value): ?Password
    {
        if ($value) {
            try {
                return new Password($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid "%s" value', $value));
            }
        }

        return null;
    }
}
