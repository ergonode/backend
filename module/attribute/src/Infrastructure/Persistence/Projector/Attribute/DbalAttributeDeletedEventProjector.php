<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeDeletedEvent;

class DbalAttributeDeletedEventProjector
{
    private const TABLE = 'attribute';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(AttributeDeletedEvent $event): void
    {
        // @todo What we should do with unused values?

        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
