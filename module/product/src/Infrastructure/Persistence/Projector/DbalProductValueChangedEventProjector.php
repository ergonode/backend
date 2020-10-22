<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class DbalProductValueChangedEventProjector extends AbstractProductValueProjector
{
    /**
     * @param ProductValueChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $productId = $event->getAggregateId()->getValue();
        $attributeId = AttributeId::fromKey($event->getAttributeCode()->getValue())->getValue();

        $this->delete($productId, $attributeId);
        $this->insertValue($productId, $attributeId, $event->getTo());
    }
}
