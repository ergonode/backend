<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;

abstract class AbstractProductProjector
{
    private const TABLE_PRODUCT = 'product';

    protected Connection $connection;

    protected AttributeQueryInterface $attributeQuery;

    public function __construct(Connection $connection, AttributeQueryInterface $attributeQuery)
    {
        $this->connection = $connection;
        $this->attributeQuery = $attributeQuery;
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
