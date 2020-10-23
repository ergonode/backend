<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Workflow\Domain\Event\Status\StatusDescriptionChangedEvent;
use JMS\Serializer\SerializerInterface;

class DbalStatusDescriptionChangedEventProjector
{
    private const TABLE = 'status';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(StatusDescriptionChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'description' => $this->serializer->serialize($event->getTo()->getTranslations(), 'json'),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
