<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Command\Relations;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;

/**
 */
class AddProductChildCommand implements DomainCommandInterface
{
    /**
     * @var ProductId $id
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
     * @param ProductId                 $childId
     */
    public function __construct(AbstractAssociatedProduct $product, ProductId $childId)
    {
        $this->id = $product->getId();
        $this->childId = $childId;
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
