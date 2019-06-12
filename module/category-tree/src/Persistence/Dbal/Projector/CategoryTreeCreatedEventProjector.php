<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Persistence\Dbal\Projector;

use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCreatedEvent;

/**
 */
class CategoryTreeCreatedEventProjector extends AbstractCategoryTreeEventProjector
{
    /**
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof CategoryTreeCreatedEvent;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws ProjectorException
     * @throws UnsupportedEventException
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof CategoryTreeCreatedEvent) {
            throw new UnsupportedEventException($event, CategoryTreeCreatedEvent::class);
        }

        try {
            $this->connection->beginTransaction();

            if ($event->getCategoryId()) {
                $this->connection->insert(
                    self::TABLE,
                    [
                        'tree_id' => $aggregateId->getValue(),
                        'category_id' => $event->getCategoryId()->getValue(),
                        'path' => $this->getSequence($event->getCategoryId()),
                    ]
                );
            }
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw new ProjectorException($event, $exception);
        }
    }
}
