<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Editor\Domain\Event\ProductDraftCreated;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class ProductDraftCreatedEventProjector implements DomainEventProjectorInterface
{
    private const DRAFT_TABLE = 'designer.draft';

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
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof ProductDraftCreated;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, ProductDraftCreated::class);
        }

        $this->connection->insert(
            self::DRAFT_TABLE,
            [
                'id' => $aggregateId->getValue(),
                'product_id' => $event->getProductId() ? $event->getProductId()->getValue() : null,
                'type' => $event->getProductId() ? 'EDITED': 'NEW',
            ]
        );
    }
}
