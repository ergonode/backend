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
use Ergonode\Exporter\Domain\Entity\Catalog\AbstractExportProduct;
use Ergonode\Exporter\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Exporter\Persistence\Dbal\Repository\Factory\ExportProductFactory;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalProductRepository implements ProductRepositoryInterface
{
    private const TABLE = 'exporter.product';
    private const FIELDS = [
        'id',
        'type',
        'data',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var ExportProductFactory
     */
    private ExportProductFactory $factory;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Connection           $connection
     * @param ExportProductFactory $factory
     * @param SerializerInterface  $serializer
     */
    public function __construct(Connection $connection, ExportProductFactory $factory, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->serializer = $serializer;
    }

    /**
     * @param Uuid $id
     *
     * @return AbstractExportProduct|null
     */
    public function load(Uuid $id): ?AbstractExportProduct
    {
        $qb = $this->getQuery();
        $record = $qb->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->toString())
            ->execute()
            ->fetch();

        if ($record) {
            return $this->factory->create($record);
        }

        return null;
    }

    /**
     * @param AbstractExportProduct $exportTree
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(AbstractExportProduct $exportTree): void
    {
        if ($this->exists($exportTree->getId())) {
            $this->update($exportTree);
        } else {
            $this->insert($exportTree);
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
     * @param AbstractExportProduct $product
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function delete(AbstractExportProduct $product): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $product->getId()->toString(),
            ]
        );
    }

    /**
     * @param AbstractExportProduct $product
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(AbstractExportProduct $product): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'data' => $this->serializer->serialize($product, 'json'),
                'type' => \get_class($product),
            ],
            [
                'id' => $product->getId()->toString(),
            ]
        );
    }

    /**
     * @param AbstractExportProduct $product
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(AbstractExportProduct $product): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $product->getId()->toString(),
                'data' => $this->serializer->serialize($product, 'json'),
                'type' => \get_class($product),
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
