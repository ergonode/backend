<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector\Product;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductValueAddedEvent;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;

/**
 */
class DbalProductValueAddedEventProjector
{
    private const TABLE_WORKFLOW_PRODUCT_STATUS = 'product_workflow_status';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductValueAddedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductValueAddedEvent $event): void
    {
        $productId = $event->getAggregateId()->getValue();
        $code = $event->getAttributeCode()->getValue();

        if (StatusSystemAttribute::CODE === $code) {
            foreach ($event->getValue()->getValue() as $language => $value) {
                $this->connection->insert(
                    self::TABLE_WORKFLOW_PRODUCT_STATUS,
                    [
                        'product_id' => $productId,
                        'status_id' => $value,
                        'language' => $language,
                    ]
                );
            }
        }
    }
}
