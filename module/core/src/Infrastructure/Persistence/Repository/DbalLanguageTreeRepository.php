<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Application\Event\LanguageTreeUpdatedEvent;
use Ergonode\Core\Domain\Entity\LanguageTree;
use Ergonode\Core\Domain\Repository\LanguageTreeRepositoryInterface;
use Ergonode\Core\Infrastructure\Builder\LanguageTree\LanguageTreeBuilder;
use Ergonode\Core\Infrastructure\Persistence\Repository\Factory\DbalLanguageTreeFactory;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class DbalLanguageTreeRepository implements LanguageTreeRepositoryInterface
{
    private const TABLE = 'language_tree';

    private Connection $connection;

    private LanguageTreeBuilder $builder;

    private DbalLanguageTreeFactory $factory;

    protected ApplicationEventBusInterface $eventBus;

    public function __construct(
        Connection $connection,
        LanguageTreeBuilder $builder,
        DbalLanguageTreeFactory $factory,
        ApplicationEventBusInterface $eventBus
    ) {
        $this->connection = $connection;
        $this->builder = $builder;
        $this->factory = $factory;
        $this->eventBus = $eventBus;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function load(): ?LanguageTree
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select('*')
            ->from(self::TABLE, 't')
            ->orderBy('t.lft')
            ->execute()
            ->fetchAll();

        if ($result) {
            return $this->factory->create($result);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function save(LanguageTree $tree): void
    {
        $buildTree = $this->builder->build($tree->getLanguages());

        try {
            $this->connection->beginTransaction();
            $this->delete();

            foreach ($buildTree->getData() as $branch) {
                $this->connection->insert(
                    self::TABLE,
                    [
                        'id' => $branch->getId()->getValue(),
                        'parent_id' => $branch->getParentId() ? $branch->getParentId()->getValue() : null,
                        'lft' => $branch->getLeft(),
                        'rgt' => $branch->getRight(),
                        'code' => $branch->getCode(),
                    ]
                );
            }
            $this->connection->commit();
        } catch (\Exception $ex) {
            $this->connection->rollBack();
            throw $ex;
        }
        $event = new LanguageTreeUpdatedEvent($tree);

        $this->eventBus->dispatch(
            $event
        );
    }

    private function delete(): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->delete(self::TABLE)
            ->where('lft > :int')
            ->setParameter(':int', 0)
            ->execute();
    }
}
