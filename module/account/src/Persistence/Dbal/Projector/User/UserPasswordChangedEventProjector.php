<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\User;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Event\User\UserPasswordChangedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class UserPasswordChangedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'users';

    /**
     * @var Connection
     */
    private $connection;

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
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof UserPasswordChangedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof UserPasswordChangedEvent) {
            throw new UnsupportedEventException($event, UserPasswordChangedEvent::class);
        }

        $this->connection->update(
            self::TABLE,
            [
                'password' => $event->getPassword(),
            ],
            [
                'id' => $aggregateId->getValue(),
            ]
        );
    }
}
