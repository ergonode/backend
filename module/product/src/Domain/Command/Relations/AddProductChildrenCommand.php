<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Relations;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

class AddProductChildrenCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var ProductId[] $children
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\ProductId>")
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
