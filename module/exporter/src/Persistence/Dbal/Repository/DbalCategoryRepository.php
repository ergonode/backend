<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportTree;
use Ergonode\Exporter\Domain\Repository\CategoryRepositoryInterface;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalCategoryRepository implements CategoryRepositoryInterface
{
    private const TABLE = 'exporter.category';
    private const FIELDS = [
        'id',
        'data',
        'code',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param Uuid $id
     *
     * @return ExportCategory|null
     */
    public function load(Uuid $id): ?ExportCategory
    {
        $qb = $this->getQuery();
        $record = $qb->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->toString())
            ->execute()
            ->fetch();

        if ($record) {
            return $this->serializer->deserialize($record['data'], ExportCategory::class, 'json');
        }

        return null;
    }

    /**
     * @param ExportCategory $category
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ExportCategory $category): void
    {
        if ($this->exists($category->getId())) {
            $this->update($category);
        } else {
            $this->insert($category);
        }
    }

    /**
     * @param Uuid $id
     *
     * @return bool
     */
    public function exists(Uuid $id): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->toString())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ExportCategory $category
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function delete(ExportCategory $category): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $category->getId()->toString(),
            ]
        );
    }

    /**
     * @param ExportCategory $category
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ExportCategory $category): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'data' => $this->serializer->serialize($category, 'json'),
                'code' => $category->getCode(),
            ],
            [
                'id' => $category->getId()->toString(),
            ]
        );
    }

    /**
     * @param ExportCategory $category
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(ExportCategory $category): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $category->getId()->toString(),
                'data' => $this->serializer->serialize($category, 'json'),
                'code' => $category->getCode(),
            ]
        );
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
