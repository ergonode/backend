<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Event;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Application\ApplicationEventInterface;

class ProductDeletedEvent implements ApplicationEventInterface
{
    private AbstractProduct $product;

    public function __construct(AbstractProduct $product)
    {
        $this->product = $product;
    }

    public function getProduct(): AbstractProduct
    {
        return $this->product;
    }
}
