<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Service;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

class SegmentProductService
{
    private const TABLE = 'segment_product';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function mark(SegmentId $segmentId, ProductId $productId): void
    {
        $this->update($segmentId, $productId, true);
    }

    /**
     * @throws DBALException
     */
    public function unmark(SegmentId $segmentId, ProductId $productId): void
    {
        $this->update($segmentId, $productId, false);
    }

    public function addProduct(ProductId $productId): void
    {
        $this->connection->executeQuery(
            'INSERT INTO segment_product (segment_id, product_id) SELECT id, ? FROM segment',
            [$productId->getValue()]
        );
    }

    public function recalculateProduct(ProductId $productId): void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->update(self::TABLE)
            ->set('calculated_at', ':calculatedAt')
            ->where($qb->expr()->eq('product_id', ':productId'))
            ->andWhere($qb->expr()->isNotNull('calculated_at'))
            ->setParameter(':productId', $productId->getValue())
            ->setParameter(':calculatedAt', null)
            ->execute();
    }

    public function removeProduct(ProductId $productId): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'product_id' => $productId->getValue(),
            ]
        );
    }

    /**
     * @throws DBALException
     */
    private function update(SegmentId $segmentId, ProductId $productId, bool $available): void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->update(self::TABLE)
            ->set('available', ':available')
            ->where($qb->expr()->eq('product_id', ':productId'))
            ->andWhere($qb->expr()->eq('segment_id', ':segmentId'))
            ->andWhere($qb->expr()->neq('available', ':available'))
            ->setParameter(':segmentId', $segmentId->getValue())
            ->setParameter(':productId', $productId->getValue())
            ->setParameter('available', $available, \PDO::PARAM_BOOL)
            ->execute();
    }
}
