<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Manager;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class CompletenessManager
{
    private const TABLE = 'product_completeness';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function addProduct(ProductId $productId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'product_id' => $productId->getValue(),
            ]
        );
    }

    public function recalculateProduct(ProductId $productId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'calculated_at' => null,
            ],
            [
                'product_id' => $productId->getValue(),
            ]
        );
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

    public function recalculateTemplate(TemplateId $templateId): void
    {
        $this->connection->executeQuery(
            'UPDATE product_completeness 
            SET calculated_at = null 
            WHERE product_id IN (SELECT id FROM product WHERE template_id = ?)',
            [$templateId->getValue()],
        );
    }

    public function updateCompleteness(ProductId $id, array $completeness): void
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
