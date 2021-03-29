<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class UpdateProductCategoriesCommand implements ProductCommandInterface
{
    private ProductId $id;

    /**
     * @var CategoryId[]
     */
    private array $categories;

    /**
     * @param CategoryId[] $categories
     */
    public function __construct(ProductId $productId, array $categories = [])
    {

        $this->id = $productId;
        $this->categories = $categories;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return CategoryId[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
