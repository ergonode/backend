<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Option;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Option\OptionCodeChangedEvent;

class DbalOptionCodeChangedEventProjector
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
    public function __invoke(OptionCodeChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE_ATTRIBUTE_OPTION,
            [
                'key' => $event->getCode()->getValue(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
