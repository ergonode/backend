<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Ergonode\Workflow\Domain\Event\Status\StatusCreatedEvent;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;

class DbalStatusCreatedEventProjector
{
    private const TABLE = 'status';

    private Connection $connection;

    private SerializerInterface $serializer;

    private StatusQueryInterface $query;

    public function __construct(
        Connection $connection,
        SerializerInterface $serializer,
        StatusQueryInterface $query
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(StatusCreatedEvent $event): void
    {
        $maxIndex = $this->query->getMaxIndex();
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'code' => $event->getCode(),
                'name' => $this->serializer->serialize($event->getName()->getTranslations()),
                'description' => $this->serializer->serialize($event->getDescription()->getTranslations()),
                'color' => $event->getColor()->getValue(),
                'index' => $maxIndex + 1,
            ]
        );
    }
}
