<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Handler;

use Doctrine\DBAL\Connection;
use Ergonode\Product\Application\Event\ProductDeletedEvent;

class AuditProductDeletedEventHandler
{
    private const TABLE = 'audit';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(ProductDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getProduct()->getId()->getValue(),
            ]
        );
    }
}
