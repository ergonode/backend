<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Service;

use Doctrine\DBAL\Types\Types;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

class SegmentProductService
{
    private const TABLE = 'segment_product';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
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
        $sql = 'INSERT INTO '.self::TABLE.' (segment_id, product_id) VALUES (:segmentId, :productId)
            ON CONFLICT ON CONSTRAINT segment_product_pkey
                DO UPDATE SET calculated_at = NULL
        ';
        $this->connection->executeQuery(
            $sql,
            [
                'segmentId' => $segmentId->getValue(),
                'productId' => $productId->getValue(),
            ],
        );
    }

    /**
     * @param SegmentId $segmentId
     * @param ProductId $productId
     *
     * @throws DBALException
     */
    public function mark(SegmentId $segmentId, ProductId $productId): void
    {
        $sql = 'INSERT INTO '.self::TABLE.' (segment_id, product_id, available, calculated_at)
            VALUES (:segmentId, :productId, true, :calculatedAt)
            ON CONFLICT ON CONSTRAINT segment_product_pkey
                DO UPDATE SET available = true
        ';
        $this->connection->executeQuery(
            $sql,
            [
                'segmentId' => $segmentId->getValue(),
                'productId' => $productId->getValue(),
                'calculatedAt' => new \DateTime(),
            ],
            [
                'calculatedAt' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @param SegmentId $segmentId
     * @param ProductId $productId
     *
     * @throws DBALException
     */
    public function unmark(SegmentId $segmentId, ProductId $productId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'available' => false,
                'calculated_at' => new \DateTime(),
            ],
            [
                'segment_id' => $segmentId->getValue(),
                'product_id' => $productId->getValue(),
            ],
            [
                'available' => \PDO::PARAM_BOOL,
                'calculated_at' => Types::DATETIMETZ_MUTABLE,
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
