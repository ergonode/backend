<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Category;

use Ergonode\Product\Domain\Command\ProductCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;

class AddProductCategoryCommand implements ProductCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private CategoryId $categoryId;

    public function __construct(ProductId $id, CategoryId $categoryId)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }
}
