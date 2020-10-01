<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeDeletedEvent;

/**
 */
class DbalAttributeDeletedEventProjector
{
    private const TABLE_ATTRIBUTE = 'exporter.attribute';

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
     * @param AttributeDeletedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(AttributeDeletedEvent $event): void
    {

        $this->connection->delete(
            self::TABLE_ATTRIBUTE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
