<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Product\Domain\Event\ProductCreated;

/**
 */
class ProductCreatedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'designer.product';

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
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof ProductCreated;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws ProjectorException
     * @throws UnsupportedEventException
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ProductCreated) {
            throw new UnsupportedEventException($event, ProductCreated::class);
        }

        try {
            $this->connection->beginTransaction();
            $this->connection->insert(
                self::TABLE,
                [
                    'product_id' => $aggregateId->getValue(),
                    'template_id' => $event->getTemplateId()->getValue(),
                ]
            );
            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();
            throw new ProjectorException($event, $exception);
        }
    }
}
