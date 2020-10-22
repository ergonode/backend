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
use JMS\Serializer\Annotation as JMS;

class RemoveProductBindingCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $bindingId;

    public function __construct(AbstractAssociatedProduct $product, AbstractAttribute $binding)
    {
        $this->id = $product->getId();
        $this->bindingId = $binding->getId();
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getBindingId(): AttributeId
    {
        return $this->bindingId;
    }
}
