<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Relations;

use Ergonode\Product\Domain\Command\ProductCommandInterface;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Webmozart\Assert\Assert;

class AddProductChildrenCommand implements ProductCommandInterface
{
    private ProductId $id;

    /**
     * @var ProductId[] $children
     */
    private array $children;

    /**
     * @param ProductId[] $children
     */
    public function __construct(AbstractAssociatedProduct $product, array $children)
    {
        Assert::allIsInstanceOf($children, ProductId::class);

        $this->id = $product->getId();
        $this->children = $children;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return ProductId[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }
}
