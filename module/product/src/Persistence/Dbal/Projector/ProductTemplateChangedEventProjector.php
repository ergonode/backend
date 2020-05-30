<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductTemplateChangedEvent;

/**
 */
class ProductTemplateChangedEventProjector
{
    private const TABLE_PRODUCT = 'product';

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
     * @param ProductTemplateChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductTemplateChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE_PRODUCT,
            [
                'template_id' => $event->getTemplateId()->getValue(),
            ],
            [
                'id' => $event->getAggregateId(),
            ]
        );
    }
}
