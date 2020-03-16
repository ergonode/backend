<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Editor\Domain\Event\ProductDraftCreated;

/**
 */
class ProductDraftCreatedEventProjector
{
    private const DRAFT_TABLE = 'designer.draft';

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
     * @param ProductDraftCreated $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductDraftCreated $event): void
    {
        $this->connection->insert(
            self::DRAFT_TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'product_id' => $event->getProductId() ? $event->getProductId()->getValue() : null,
                'type' => $event->getProductId() ? 'EDITED' : 'NEW',
            ]
        );
    }
}
