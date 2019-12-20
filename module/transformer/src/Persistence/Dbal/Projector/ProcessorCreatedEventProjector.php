<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Transformer\Domain\Event\ProcessorCreatedEvent;
use Ergonode\Transformer\Domain\ValueObject\ProcessorStatus;

/**
 */
class ProcessorCreatedEventProjector
{
    /**
     * @var Connection
     */
    private $connection;

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
    public function __invoke(ProcessorCreatedEvent $event): void
    {
        $date = date('Y-m-d H:i:s');

        $this->connection->insert(
            'importer.processor',
            [
                'created_at' => $date,
                'updated_at' => $date,
                'id' => $event->getAggregateId()->getValue(),
                'import_id' => $event->getImportId()->getValue(),
                'transformer_id' => $event->getTransformerId()->getValue(),
                'action' => $event->getAction(),
                'status' => ProcessorStatus::CREATED,
            ]
        );
    }
}
