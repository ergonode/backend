<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributePrice\Domain\Query;

/**
 */
interface CurrencyQueryInterface
{
    /**
     * @return array
     */
    public function getDictionary(): array;
}
