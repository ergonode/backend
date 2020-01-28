<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Entity\AbstractProduct;
use Ergonode\Exporter\Domain\Entity\Product\SimpleProduct;
use Ergonode\Exporter\Domain\Repository\ProductRepositoryInterface;
use JMS\Serializer\SerializerInterface;

/**
 */
class DbalProductRepository implements ProductRepositoryInterface
{
    private const TABLE_PRODUCT = 'exporter.product';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * DbalProductRepository constructor.
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
     * @return AbstractProduct|null
     */
    public function load(string $id): ?AbstractProduct
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('*')
            ->from(self::TABLE_PRODUCT)
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id)
            ->execute()
            ->fetch();

        //todo if not or other product type or exeption
        return $this->serializer->deserialize($result['data'], SimpleProduct::class, 'json');
    }

    /**
     * @param AbstractProduct $product
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(AbstractProduct $product): void
    {
        $this->connection->update(
            self::TABLE_PRODUCT,
            [
                'data' => $this->serializer->serialize($product, 'json'),
            ],
            [
                'id' => $product->getId(),
            ]
        );
    }
}
