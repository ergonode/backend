<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Service;

use Ergonode\Segment\Domain\Entity\SegmentId;
use Ergonode\Product\Domain\Entity\ProductId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

/**
 */
class SegmentProductService
{
    private const TABLE = 'segment_product';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * SegmentProductService constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param SegmentId $segmentId
     * @param ProductId $productId
     *
     * @throws DBALException
     */
    public function add(SegmentId $segmentId, ProductId $productId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'segment_id' => $segmentId->getValue(),
                'product_id' => $productId->getValue(),
            ]
        );
    }

    /**
     * @param SegmentId $segmentId
     * @param ProductId $productId
     *
     * @throws DBALException
     */
    public function remove(SegmentId $segmentId, ProductId $productId): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'segment_id' => $segmentId->getValue(),
                'product_id' => $productId->getValue(),
            ]
        );
    }

    /**
     * @param SegmentId $segmentId
     * @param ProductId $productId
     *
     * @return bool
     */
    public function exists(SegmentId $segmentId, ProductId $productId): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb
            ->select('*')
            ->from(self::TABLE)
            ->andWhere($qb->expr()->eq('segment_id', ':segmentId'))
            ->andWhere($qb->expr()->eq('product_id', ':productId'))
            ->setParameter(':segmentId', $segmentId->getValue())
            ->setParameter(':productId', $productId->getValue())
            ->execute()
            ->fetch();

        if ($result) {
            return true;
        }

        return false;
    }
}
