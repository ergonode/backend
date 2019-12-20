<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;

/**
 */
class MultimediaCreatedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'multimedia';

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof MultimediaCreatedEvent;
    }

    /**
     * @inheritDoc
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, MultimediaCreatedEvent::class);
        }

        /**
         * @var $event MultimediaCreatedEvent
         */
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'extension' => $event->getExtension(),
                'size' => $event->getSize(),
                'mime' => $event->getMime(),
                'crc' => $event->getCrc(),
            ]
        );
    }
}
