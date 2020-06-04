<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model\Product\Relation;

use Ergonode\Product\Infrastructure\Validator\SkusValid;

/**
 */
class ProductChildBySkuCollectionFormModel
{
    /**
     * @var string[]|null
     *
    /**
     * @var string|null
     *
     * @SkusValid()
     */
    public ?string $skus = null;
}
