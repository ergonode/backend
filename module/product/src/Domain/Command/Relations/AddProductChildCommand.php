<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Relations;

use Ergonode\Product\Domain\Command\ProductCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;

class AddProductChildCommand implements ProductCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $childId;

    public function __construct(AbstractAssociatedProduct $product, ProductId $childId)
    {
        $this->id = $product->getId();
        $this->childId = $childId;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getChildId(): ProductId
    {
        return $this->childId;
    }
}
