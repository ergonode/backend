<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Value\Domain\Service;

use Ergonode\Value\Domain\ValueObject\ValueInterface;

interface ValueUpdateStrategyInterface
{
    public function isSupported(ValueInterface $oldValue): bool;

    public function calculate(ValueInterface $oldValue, ValueInterface $newValue): ValueInterface;
}
