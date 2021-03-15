<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeScopeChangedEvent;

class DbalAttributeScopeChangedEventProjector
{
    private const TABLE = 'attribute';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(AttributeScopeChangedEvent $event): void
    {
        if (!empty($event->getTo())) {
            $this->connection->update(
                self::TABLE,
                [
                    'scope' => $event->getTo()->getValue(),
                ],
                [
                    'id' => $event->getAggregateId()->getValue(),
                ]
            );
        }
    }
}
