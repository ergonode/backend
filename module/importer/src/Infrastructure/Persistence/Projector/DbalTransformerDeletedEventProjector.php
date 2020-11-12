<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Importer\Domain\Event\TransformerDeletedEvent;

class DbalTransformerDeletedEventProjector
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(TransformerDeletedEvent $event): void
    {
        $this->connection->delete(
            'importer.transformer',
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
