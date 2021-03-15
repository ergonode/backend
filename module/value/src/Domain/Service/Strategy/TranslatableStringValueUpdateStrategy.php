<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\Service\Strategy;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\Service\ValueUpdateStrategyInterface;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class TranslatableStringValueUpdateStrategy implements ValueUpdateStrategyInterface
{
    public function isSupported(ValueInterface $oldValue): bool
    {
        return $oldValue instanceof TranslatableStringValue;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(ValueInterface $oldValue, ValueInterface $newValue): ValueInterface
    {
        if (!$oldValue instanceof TranslatableStringValue) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Old value must be type %s, given %s',
                    TranslatableStringValue::class,
                    \get_class($oldValue)
                )
            );
        }

        if (!$newValue instanceof TranslatableStringValue) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'New value must be type %s, given %s',
                    TranslatableStringValue::class,
                    \get_class($newValue)
                )
            );
        }

        $calculatedTranslation = array_merge($oldValue->getValue(), $newValue->getValue());

        return new TranslatableStringValue(new TranslatableString($calculatedTranslation));
    }
}
