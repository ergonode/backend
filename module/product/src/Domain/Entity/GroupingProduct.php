<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Entity;

class GroupingProduct extends AbstractAssociatedProduct
{
    public const TYPE = 'GROUPING-PRODUCT';

    public function getType(): string
    {
        return self::TYPE;
    }
}
