<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class BooleanDataTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return bool|int|mixed
     */
    public function transform($value)
    {
        if ('true' === $value) {
            return 1;
        }
        if ('false' === $value) {
            return false;
        }
    }

    /**
     * @param mixed $value
     *
     * @return bool|mixed
     */
    public function reverseTransform($value)
    {
        // phpcs:ignore
        if (1 == $value || 'true' === $value) {
            return 'true';
        }
        // phpcs:ignore
        if (false == $value || 'false' === $value) {
            return 'false';
        }
        throw new TransformationFailedException('Expect boolean');
    }
}
