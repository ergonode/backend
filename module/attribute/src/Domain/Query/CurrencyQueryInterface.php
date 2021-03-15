<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Query;

interface CurrencyQueryInterface
{
    /**
     * @return array
     */
    public function getDictionary(): array;
}
