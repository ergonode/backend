<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\Service\Strategy;

use Ergonode\Value\Domain\Service\ValueUpdateStrategyInterface;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class StringCollectionValueUpdateStrategy implements ValueUpdateStrategyInterface
{
    public function isSupported(ValueInterface $oldValue): bool
    {
        return $oldValue instanceof StringCollectionValue;
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

        $calculatedTranslation = array_merge($oldValue->getValue(), $newValue->getValue());

        return new StringCollectionValue($calculatedTranslation);
    }
}
