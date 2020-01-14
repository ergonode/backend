<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\Service\Strategy;

use Ergonode\Value\Domain\Service\ValueUpdateStrategyInterface;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class StringValueToTranslatableStringValueUpdateStrategy implements ValueUpdateStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function isSupported(ValueInterface $oldValue, ValueInterface $newValue): bool
    {
        return $oldValue instanceof StringValue && $newValue instanceof TranslatableStringValue;
    }

    /**
     * @inheritDoc
     */
    public function calculate(ValueInterface $oldValue, ValueInterface $newValue): ValueInterface
    {
        return clone $newValue;
    }
}
