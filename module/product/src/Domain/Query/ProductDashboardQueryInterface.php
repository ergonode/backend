<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;

interface ProductDashboardQueryInterface
{
    /**
     * @return array
     */
    public function getProductCount(Language $language): array;
}
