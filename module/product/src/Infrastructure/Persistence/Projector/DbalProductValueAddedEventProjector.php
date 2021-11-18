<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductValueAddedEvent;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Webmozart\Assert\Assert;

class DbalProductValueAddedEventProjector extends AbstractProductValueProjector
{
    /**
     * @throws DBALException
     */
    public function __invoke(ProductValueAddedEvent $event): void
    {
        $productId = $event->getAggregateId();
        $code = $event->getAttributeCode();

        $attributeId = $this->attributeQuery->findAttributeIdByCode($code);
        Assert::notNull($attributeId);
        if (StatusSystemAttribute::CODE !== $code->getValue()) {
            $this->insertValue($productId->getValue(), $attributeId->getValue(), $event->getValue());
        }
        $this->updateAudit($event->getAggregateId());
    }
}
