<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

interface AttributeValueQueryInterface
{
    /**
     * @return array
     */
    public function getUniqueValue(AttributeId $attributeId): array;
}
