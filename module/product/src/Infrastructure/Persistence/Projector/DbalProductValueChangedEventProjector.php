<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Webmozart\Assert\Assert;

class DbalProductValueChangedEventProjector extends AbstractProductValueProjector
{
    /**
     * @throws DBALException
     */
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $productId = $event->getAggregateId()->getValue();
        $attributeId = $this->attributeQuery->findAttributeIdByCode($event->getAttributeCode());
        Assert::notNull($attributeId);

        $this->delete($productId, $attributeId->getValue());
        $this->insertValue($productId, $attributeId->getValue(), $event->getTo());
        $this->updateAudit($event->getAggregateId());
    }
}
