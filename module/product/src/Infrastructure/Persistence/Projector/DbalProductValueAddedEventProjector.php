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
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Doctrine\DBAL\Connection;

class DbalProductValueAddedEventProjector extends AbstractProductValueProjector
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
