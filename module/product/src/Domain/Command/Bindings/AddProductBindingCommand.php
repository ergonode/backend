<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Command\Bindings;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;

class AddProductBindingCommand implements DomainCommandInterface
{
    /**
     * @var ProductId $id
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
     * @param AttributeId               $attributeId
     */
    public function __construct(AbstractAssociatedProduct $product, AttributeId $attributeId)
    {
        $this->id = $product->getId();
        $this->bindingId = $attributeId;
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
