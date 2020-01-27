<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Category;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Category\Domain\Event\CategoryDeletedEvent;

/**
 */
class CategoryDeletedEventProjector
{
    private const TABLE_CATEGORY = 'exporter.category';
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * CategoryDeletedEventProjector constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param CategoryDeletedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(CategoryDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE_CATEGORY,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
