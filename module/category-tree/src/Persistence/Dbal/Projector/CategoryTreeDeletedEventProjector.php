<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\Role;

use Doctrine\DBAL\Connection;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeDeletedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class CategoryTreeDeletedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'tree';

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
        return $event instanceof CategoryTreeDeletedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof CategoryTreeDeletedEvent) {
            throw new UnsupportedEventException($event, CategoryTreeDeletedEvent::class);
        }

        $this->connection->delete(
            self::TABLE,
            [
                'id' => $aggregateId->getValue(),
            ]
        );
    }
}
