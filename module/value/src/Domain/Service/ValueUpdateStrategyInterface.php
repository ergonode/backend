<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\Service;

use Ergonode\Value\Domain\ValueObject\ValueInterface;

interface ValueUpdateStrategyInterface
{
    public function isSupported(ValueInterface $oldValue): bool;

    public function calculate(ValueInterface $oldValue, ValueInterface $newValue): ValueInterface;
}
