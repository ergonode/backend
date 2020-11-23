<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Service;

use Doctrine\DBAL\Types\Types;
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
     * @throws DBALException
     */
    public function addBySegment(SegmentId $segmentId): void
    {
        $sql = 'INSERT INTO  '.self::TABLE.' (segment_id, product_id)
                    SELECT :segmentId, p.id 
                    FROM product p
                ON CONFLICT ON CONSTRAINT segment_product_pkey
                DO UPDATE SET calculated_at = NULL
        ';
        $this->connection->executeQuery(
            $sql,
            [
                'segmentId' => $segmentId->getValue(),
            ],
        );
    }

    /**
     * @throws DBALException
     */
    public function addByProduct(ProductId $productId): void
    {
        $sql = 'INSERT INTO  '.self::TABLE.' (segment_id, product_id)
                    SELECT s.id, :productId
                    FROM segment s
                ON CONFLICT ON CONSTRAINT segment_product_pkey
                DO UPDATE SET calculated_at = NULL
        ';
        $this->connection->executeQuery(
            $sql,
            [
                'productId' => $productId->getValue(),
            ],
        );
    }

    /**
     * @throws DBALException
     */
    public function mark(SegmentId $segmentId, ProductId $productId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'available' => true,
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
