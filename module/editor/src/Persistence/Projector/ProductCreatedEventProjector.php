<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;

/**
 */
class ProductCreatedEventProjector
{
    private const TABLE = 'designer.product';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductCreatedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(ProductCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'product_id' => $event->getAggregateId()->getValue(),
                'template_id' => $event->getTemplateId()->getValue(),
            ]
        );
    }
}
