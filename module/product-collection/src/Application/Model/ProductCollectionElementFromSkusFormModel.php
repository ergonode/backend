<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\Product\Infrastructure\Validator\SkusValid;

/**
 */
class ProductCollectionElementFromSkusFormModel
{
    /**
     * @var string|null
     *
     * @SkusValid()
     */
    public ?string $skus;

    /**
     */
    public function __construct()
    {
        $this->skus = null;
    }
}
