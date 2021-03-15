<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Option;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Option\OptionRemovedEvent;

class DbalOptionRemovedEventProjector
{
    private const TABLE_ATTRIBUTE_OPTION = 'attribute_option';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(OptionRemovedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE_ATTRIBUTE_OPTION,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
