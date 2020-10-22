<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Command\Relations;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use JMS\Serializer\Annotation as JMS;

class RemoveProductChildCommand implements DomainCommandInterface
{
    /**
     * @var ProductId $productId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var ProductId $childId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $childId;

    /**
     * @param AbstractAssociatedProduct $product
     * @param AbstractProduct           $child
     */
    public function __construct(AbstractAssociatedProduct $product, AbstractProduct $child)
    {
        $this->id = $product->getId();
        $this->childId = $child->getId();
    }

    /**
     * @return ProductId
     */
    public function getId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return ProductId
     */
    public function getChildId(): ProductId
    {
        return $this->childId;
    }
}
