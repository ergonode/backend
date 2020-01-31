<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Transformer\Domain\Event\TransformerCreatedEvent;

/**
 */
class TransformerCreatedEventProjector
{
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
