<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use Ergonode\Exporter\Domain\Repository\CategoryRepositoryInterface;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

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
     *
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
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('*')
            ->from(self::TABLE_CATEGORY)
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->toString())
            ->execute()
            ->fetch();

        //todo if not or other product type or exeption
        return $this->serializer->deserialize($result['data'], ExportCategory::class, 'json');
    }

    /**
     * @param ExportCategory $category
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ExportCategory $category): void
    {
        $this->connection->update(
            self::TABLE_CATEGORY,
            [
                'data' => $this->serializer->serialize($category, 'json'),
                'code' => $category->getCode(),
            ],
            [
                'id' => $category->getId()->toString(),
            ]
        );
    }
}
