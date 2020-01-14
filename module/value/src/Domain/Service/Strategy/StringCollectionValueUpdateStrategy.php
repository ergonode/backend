<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\Service\Strategy;

use Ergonode\Value\Domain\Service\ValueUpdateStrategyInterface;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class StringCollectionValueUpdateStrategy implements ValueUpdateStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function isSupported(ValueInterface $oldValue, ValueInterface $newValue): bool
    {
        return $oldValue instanceof StringCollectionValue && $newValue instanceof StringCollectionValue;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(ValueInterface $oldValue, ValueInterface $newValue): ValueInterface
    {
        if (!$oldValue instanceof StringCollectionValue) {
            throw new \InvalidArgumentException(\sprintf('New value must be type %s', StringCollectionValue::class));
        }

        if (!$newValue instanceof StringCollectionValue) {
            throw new \InvalidArgumentException(\sprintf('New value must be type %s', StringCollectionValue::class));
        }

        return new StringCollectionValue($newValue->getValue());
    }
}
