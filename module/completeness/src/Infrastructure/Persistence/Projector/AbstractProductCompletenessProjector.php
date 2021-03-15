<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

abstract class AbstractProductCompletenessProjector
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function update(ProductId $id): void
    {
        $this->connection->update(
            'product_completeness',
            [
                'calculated_at' => null,
            ],
            [
                'product_id' => $id->getValue(),
            ]
        );
    }
}
