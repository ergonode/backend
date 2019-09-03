<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Product\Domain\Event\ProductVersionIncreased;

/**
 */
class ProductVersionIncreasedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE_PRODUCT = 'product';

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
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof ProductVersionIncreased;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ProductVersionIncreased) {
            throw new UnsupportedEventException($event, ProductVersionIncreased::class);
        }

        $this->connection->update(
            self::TABLE_PRODUCT,
            [
                'version' => $event->getTo(),
            ],
            [
                'id' => $aggregateId->getValue(),
            ]
        );
    }
}
