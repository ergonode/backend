<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Transformer\Domain\Event\ProcessorStatusChangedEvent;
use Ergonode\Transformer\Domain\ValueObject\ProcessorStatus;

/**
 */
class ProcessorStatusChangedEventProjector
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
    public function __invoke(ProcessorStatusChangedEvent $event): void
    {
        $status = null;
        if ($event->getTo()->isProcessed()) {
            $status = ProcessorStatus::PRECESSED;
        } elseif ($event->getTo()->isEnded()) {
            $status = ProcessorStatus::ENDED;
        } elseif ($event->getTo()->isStopped()) {
            $status = ProcessorStatus::STOPPED;
        }

        if (null !== $status) {
            $date = date('Y-m-d H:i:s');
            $this->connection->update(
                'importer.processor',
                [
                    'updated_at' => $date,
                    'started_at' => $date,
                    'status' => $status,
                ],
                [
                    'id' => $event->getAggregateId()->getValue(),
                ]
            );
        }
    }
}
