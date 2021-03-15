<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

abstract class AbstractTemplateCompletenessProjector
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function update(TemplateId $templateId): void
    {
        $this->connection->executeQuery(
            'UPDATE product_completeness 
            SET calculated_at = null 
            WHERE product_id IN (SELECT id FROM product WHERE template_id = ?)',
            [$templateId->getValue()],
        );
    }
}
