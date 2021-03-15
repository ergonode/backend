<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\Service\Strategy;

use Ergonode\Value\Domain\Service\ValueUpdateStrategyInterface;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class StringValueUpdateStrategy implements ValueUpdateStrategyInterface
{
    public function isSupported(ValueInterface $oldValue): bool
    {
        return $oldValue instanceof StringValue;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(ValueInterface $oldValue, ValueInterface $newValue): ValueInterface
    {
        if (!$oldValue instanceof StringValue) {
            throw new \InvalidArgumentException(
                \sprintf('Old value must be type %s given %s', StringValue::class, get_class($newValue))
            );
        }

        if (!$newValue instanceof StringValue) {
            throw new \InvalidArgumentException(
                \sprintf('New value must be type %s given %s ', StringValue::class, get_class($newValue))
            );
        }

        return $newValue;
    }
}
