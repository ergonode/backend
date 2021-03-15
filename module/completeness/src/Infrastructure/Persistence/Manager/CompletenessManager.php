<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Manager;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class CompletenessManager
{
    private const TABLE = 'product_completeness';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function update(ProductId $id, array $completeness): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'completeness' => json_encode($completeness, JSON_THROW_ON_ERROR),
            ],
            [
                'product_id' => $id->getValue(),
            ]
        );
    }
}
