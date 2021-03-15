<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductValueRemovedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class DbalProductValueRemovedEventProjector extends AbstractProductValueProjector
{
    /**
     * @throws DBALException
     */
    public function __invoke(ProductValueRemovedEvent $event): void
    {
        $productId = $event->getAggregateId()->getValue();
        $attributeId = AttributeId::fromKey($event->getAttributeCode()->getValue())->getValue();

        $this->delete($productId, $attributeId);
        $this->updateAudit($event->getAggregateId());
    }
}
