<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Editor\Domain\Event\ProductDraftApplied;

/**
 */
class ProductDraftAppliedEventProjector
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
     * @param ProductDraftApplied $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductDraftApplied $event): void
    {
        $this->connection->update(
            self::DRAFT_TABLE,
            [
                'applied' => true,
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ],
            [
                'applied' => \PDO::PARAM_BOOL,
            ]
        );
    }
}
