<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Importer\Domain\Event\TransformerCreatedEvent;

class DbalTransformerCreatedEventProjector
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(TransformerCreatedEvent $event): void
    {
        $this->connection->insert(
            'importer.transformer',
            [
                'id' => $event->getAggregateId()->getValue(),
                'name' => $event->getName(),
                'key' => $event->getKey(),
            ]
        );
    }
}
