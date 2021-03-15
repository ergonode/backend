<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductValueAddedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;

class DbalProductValueAddedEventProjector extends AbstractProductValueProjector
{
    /**
     * @throws DBALException
     */
    public function __invoke(ProductValueAddedEvent $event): void
    {
        $productId = $event->getAggregateId()->getValue();
        $code = $event->getAttributeCode()->getValue();

        $attributeId = AttributeId::fromKey($event->getAttributeCode()->getValue())->getValue();
        if (StatusSystemAttribute::CODE !== $code) {
            $this->insertValue($productId, $attributeId, $event->getValue());
        }
        $this->updateAudit($event->getAggregateId());
    }
}
