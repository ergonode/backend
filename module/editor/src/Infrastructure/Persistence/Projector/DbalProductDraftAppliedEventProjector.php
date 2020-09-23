<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Editor\Domain\Event\ProductDraftApplied;

/**
 */
class DbalProductDraftAppliedEventProjector
{
    private const PRODUCT_TABLE = 'product';
    private const DRAFT_TABLE = 'designer.draft';

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
     * @param ProductDraftApplied $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductDraftApplied $event): void
    {
        $this->connection->update(
            self::DRAFT_TABLE,
            [
                'applied' => true,
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ],
            [
                'applied' => \PDO::PARAM_BOOL,
            ]
        );

        $qb = $this->connection->createQueryBuilder();
        $productId = $qb->select('product_id')
            ->from(self::DRAFT_TABLE)
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter('id', $event->getAggregateId()->getValue())
            ->setMaxResults(1)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        $updatedAt = (new \DateTime())->format(\DateTime::W3C);
        $this->connection->update(
            self::PRODUCT_TABLE,
            [
                'updated_at' => $updatedAt,
            ],
            [
                'id' => $productId,
            ]
        );
    }
}
