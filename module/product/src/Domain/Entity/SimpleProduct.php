<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Entity;

class SimpleProduct extends AbstractProduct
{
    public const TYPE = 'SIMPLE-PRODUCT';

    public function getType(): string
    {
        return self::TYPE;
    }
}
