<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\LanguageTree;
use Ergonode\Core\Domain\Repository\LanguageTreeRepositoryInterface;
use Ergonode\Core\Infrastructure\Builder\LanguageTree\LanguageTreeBuilder;
use Ergonode\Core\Infrastructure\Persistence\Repository\Factory\DbalLanguageTreeFactory;

class DbalLanguageTreeRepository implements LanguageTreeRepositoryInterface
{
    private const TABLE = 'language_tree';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var LanguageTreeBuilder
     */
    private LanguageTreeBuilder $builder;

    /**
     * @var DbalLanguageTreeFactory
     */
    private DbalLanguageTreeFactory $factory;

    /**
     * @param Connection              $connection
     * @param LanguageTreeBuilder     $builder
     * @param DbalLanguageTreeFactory $factory
     */
    public function __construct(Connection $connection, LanguageTreeBuilder $builder, DbalLanguageTreeFactory $factory)
    {
        $this->connection = $connection;
        $this->builder = $builder;
        $this->factory = $factory;
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
