<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Persistence\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserLanguageChangedEvent;

class DbalUserLanguageChangedEventProjector
{
    private const TABLE = 'users';

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
     * @param UserLanguageChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(UserLanguageChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'language' => $event->getTo()->getCode(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
