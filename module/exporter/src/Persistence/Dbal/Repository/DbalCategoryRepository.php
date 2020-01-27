<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Entity\Category;
use Ergonode\Exporter\Domain\Repository\CategoryRepositoryInterface;
use JMS\Serializer\SerializerInterface;

/**
 */
class DbalCategoryRepository implements CategoryRepositoryInterface
{
    private const TABLE_CATEGORY = 'exporter.category';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * DbalCategoryRepository constructor.
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param string $id
     *
     * @return Category|null
     */
    public function load(string $id): ?Category
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('*')
            ->from(self::TABLE_CATEGORY)
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id)
            ->execute()
            ->fetch();

        //todo if not or other product type or exeption
        return $this->serializer->deserialize($result['data'], Category::class, 'json');
    }

    /**
     * @param Category $category
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(Category $category): void
    {
        $this->connection->update(
            self::TABLE_CATEGORY,
            [
                'data' => $this->serializer->serialize($category, 'json'),
            ],
            [
                'id' => $category->getId(),
            ]
        );
    }
}
