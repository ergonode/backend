<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Workflow\Domain\Event\Status\StatusColorChangedEvent;

/**
 */
class StatusColorChangedEventProjector
{
    private const TABLE = 'status';

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
     * {@inheritDoc}
     */
    public function __invoke(StatusColorChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'color' => $event->getTo()->getValue(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
