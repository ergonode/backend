<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Query;

/**
 * Class ProductStatusQuery
 */
interface ProductStatusQueryInterface
{
    /**
     * @return array
     */
    public function getCodes(): array;
}
