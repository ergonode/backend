<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Editor\Domain\Event\ProductDraftApplied;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class ProductDraftAppliedEventProjector implements DomainEventProjectorInterface
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
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof ProductDraftApplied;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ProductDraftApplied) {
            throw new UnsupportedEventException($event, ProductDraftApplied::class);
        }

        $this->connection->update(
            self::DRAFT_TABLE,
            [
                'applied' => true,
            ],
            [
                'id' => $aggregateId->getValue(),
            ],
            [
                'applied' => \PDO::PARAM_BOOL,
            ]
        );
    }
}
