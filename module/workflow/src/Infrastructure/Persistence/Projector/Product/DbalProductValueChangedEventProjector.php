<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector\Product;

use Doctrine\DBAL\Connection;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;

class DbalProductValueChangedEventProjector
{
    private const TABLE_WORKFLOW_PRODUCT_STATUS = 'product_workflow_status';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $code = $event->getAttributeCode()->getValue();
        if (StatusSystemAttribute::CODE === $code) {
            $sql = 'INSERT INTO '.self::TABLE_WORKFLOW_PRODUCT_STATUS.'(product_id, status_id, "language")
                VALUES(:productId, :statusId, :language)
                ON CONFLICT (product_id,"language") DO UPDATE SET status_id = :statusId
                ';
            foreach ($event->getTo()->getValue() as $language => $value) {
                $this->connection->executeQuery(
                    $sql,
                    [
                        'productId' => $event->getAggregateId()->getValue(),
                        'statusId' => $value,
                        'language' => $language,
                    ],
                );
            }
        }
    }
}
