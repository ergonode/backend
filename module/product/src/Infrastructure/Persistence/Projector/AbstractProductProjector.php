<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\AggregateId;

abstract class AbstractProductProjector
{
    private const TABLE_PRODUCT = 'product';

    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function updateAudit(AggregateId $id): void
    {
        $editedAt = (new \DateTime())->format(\DateTime::W3C);
        $this->connection->update(
            self::TABLE_PRODUCT,
            [
                'updated_at' => $editedAt,
            ],
            [
                'id' => $id->getValue(),
            ]
        );
    }
}
