<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Create;

use Ergonode\Product\Domain\Command\ProductCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface CreateProductCommandInterface extends ProductCommandInterface
{
    public function getId(): ProductId;
}
