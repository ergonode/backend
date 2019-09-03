<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\Role;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Event\Role\RoleDescriptionChangedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class RoleDescriptionChangedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'roles';

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
        return $event instanceof RoleDescriptionChangedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof RoleDescriptionChangedEvent) {
            throw new UnsupportedEventException($event, RoleDescriptionChangedEvent::class);
        }

        $this->connection->update(
            self::TABLE,
            [
                'description' => $event->getTo(),
            ],
            [
                'id' => $aggregateId->getValue(),
            ]
        );
    }
}
