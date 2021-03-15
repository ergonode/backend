<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Group;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupDeletedEvent;

class DbalAttributeGroupDeletedEventProjector
{
    private const TABLE = 'attribute_group';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(AttributeGroupDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
