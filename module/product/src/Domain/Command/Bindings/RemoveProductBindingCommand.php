<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Command\Bindings;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

/**
 */
class RemoveProductBindingCommand implements DomainCommandInterface
{
    /**
     * @var ProductId $productId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var AttributeId $childId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $bindingId;

    /**
     * @param AbstractAssociatedProduct $product
     * @param AbstractAttribute         $binding
     */
    public function __construct(AbstractAssociatedProduct $product, AbstractAttribute $binding)
    {
        $this->id = $product->getId();
        $this->bindingId = $binding->getId();
    }

    /**
     * @return ProductId
     */
    public function getId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return AttributeId
     */
    public function getBindingId(): AttributeId
    {
        return $this->bindingId;
    }
}
