<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Persistence\Dbal\Projector;

use Doctrine\DBAL\DBALException;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCategoriesChangedEvent;
use Ergonode\CategoryTree\Domain\ValueObject\Node;
use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Category\Domain\Entity\CategoryId;

/**
 */
class CategoryTreeCategoriesChangedEventProjector extends AbstractCategoryTreeEventProjector
{
    /**
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof CategoryTreeCategoriesChangedEvent;
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
        if (!$event instanceof CategoryTreeCategoriesChangedEvent) {
            throw new UnsupportedEventException($event, CategoryTreeCategoriesChangedEvent::class);
        }

        try {
            $this->connection->beginTransaction();

            $this->connection->delete(
                self::TABLE,
                [
                    'tree_id' => $aggregateId->getValue(),
                ]
            );

            foreach ($event->getCategories() as $category) {
                $this->addCategory($aggregateId, $category);
            }

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw new ProjectorException($event, $exception);
        }
    }

    /**
     * @param AbstractId $id
     * @param Node       $node
     *
     * @throws DBALException
     */
    private function addCategory(AbstractId $id, Node $node): void
    {
        $sequence = $this->getSequence($node->getCategoryId());
        if ($node->getParent()) {
            $path = sprintf('%s.%s', $this->getPath($id, $node->getParent()->getCategoryId()), $sequence);
        } else {
            $path = $sequence;
        }

        $this->connection->insert(
            self::TABLE,
            [
                'tree_id' => $id,
                'category_id' => $node->getCategoryId()->getValue(),
                'path' => $path,
            ]
        );

        foreach ($node->getChildrens() as $children) {
            $this->addCategory($id, $children);
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

        $result = $qb->select('path')
            ->from(self::TABLE)
            ->where($qb->expr()->eq('tree_id', ':treeId'))
            ->andWhere($qb->expr()->eq('category_id', ':categoryId'))
            ->setParameter(':treeId', $id->getValue())
            ->setParameter(':categoryId', $parentId->getValue())
            ->execute()
            ->fetchColumn();

        if ($result) {
            return (string) $result;
        }

        return '';
    }
}
