<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\Service\Strategy;

use Ergonode\Value\Domain\ValueObject\CollectionValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Domain\Service\ValueUpdateStrategyInterface;

/**
 */
class CollectionValueUpdateStrategy implements ValueUpdateStrategyInterface
{
    /**
     * @param ValueInterface $oldValue
     *
     * @return bool
     */
    public function isSupported(ValueInterface $oldValue): bool
    {
        return $oldValue instanceof CollectionValue;
    }

    /**
     * @param ValueInterface|CollectionValue $oldValue
     * @param ValueInterface|CollectionValue $newValue
     *
     * @return ValueInterface
     */
    public function calculate(ValueInterface $oldValue, ValueInterface $newValue): ValueInterface
    {
        if (!$oldValue instanceof CollectionValue) {
            throw new \InvalidArgumentException(\sprintf('New value must be type %s', CollectionValue::class));
        }

        if (!$newValue instanceof CollectionValue) {
            throw new \InvalidArgumentException(\sprintf('New value must be type %s', CollectionValue::class));
        }

        return new CollectionValue($newValue->getValue());
    }
}
