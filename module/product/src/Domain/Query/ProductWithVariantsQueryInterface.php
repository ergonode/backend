<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\SharedKernel\Domain\AggregateId;

interface ProductWithVariantsQueryInterface
{
    /**
     * @return array
     */
    public function findProductIdsWithBoundAttributeByAttributeId(AggregateId $id): array;
}
