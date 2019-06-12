<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductSimple\Domain\Entity;

use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class SimpleProduct extends AbstractProduct
{
    public const TYPE = 'SIMPLE-PRODUCT';
}
