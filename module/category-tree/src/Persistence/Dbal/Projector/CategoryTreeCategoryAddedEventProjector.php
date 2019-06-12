<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Persistence\Dbal\Projector;

use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCategoryAddedEvent;

/**
 */
class CategoryTreeCategoryAddedEventProjector extends AbstractCategoryTreeEventProjector
{
    /**
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof CategoryTreeCategoryAddedEvent;
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
        if (!$event instanceof CategoryTreeCategoryAddedEvent) {
            throw new UnsupportedEventException($event, CategoryTreeCategoryAddedEvent::class);
        }

        try {
            $this->connection->beginTransaction();

            $sequence = $this->getSequence($event->getId());
            if ($event->getParentId()) {
                $path = sprintf('%s.%s', $this->getPath($aggregateId, $event->getParentId()), $sequence);
            } else {
                $path = $sequence;
            }
            $this->connection->insert(
                self::TABLE,
                [
                    'tree_id' => $aggregateId->getValue(),
                    'category_id' => $event->getId()->getValue(),
                    'path' => $path,
                ]
            );

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw new ProjectorException($event, $exception);
        }
    }



    /**
     * @param AbstractId $id
     * @param CategoryId $parentId
     *
     * @return string
     */
    private function getPath(AbstractId $id, CategoryId $parentId): string
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('path')
            ->from(self::TABLE)
            ->where($qb->expr()->eq('tree_id', ':treeId'))
            ->andWhere($qb->expr()->eq('category_id', ':categoryId'))
            ->setParameter(':treeId', $id->getValue())
            ->setParameter(':categoryId', $parentId->getValue())
            ->execute()
            ->fetchColumn();
    }
}
