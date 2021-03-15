<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Persistence\Projector\Unit;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Core\Domain\Event\UnitSymbolChangedEvent;

class DbalUnitSymbolChangedEventProjector
{
    private const TABLE = 'unit';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(UnitSymbolChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'symbol' => $event->getTo(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
