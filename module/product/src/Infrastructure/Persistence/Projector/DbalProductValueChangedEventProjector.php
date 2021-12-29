<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Doctrine\DBAL\Connection;

class DbalProductValueChangedEventProjector extends AbstractProductValueProjector
{
    private AttributeQueryInterface $attributeQuery;

    public function __construct(Connection $connection, AttributeQueryInterface $attributeQuery)
    {
        parent::__construct($connection);

        $this->attributeQuery = $attributeQuery;
    }

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
    }
}
