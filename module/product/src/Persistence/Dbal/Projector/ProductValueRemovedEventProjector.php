<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Product\Domain\Event\ProductValueRemoved;

/**
 */
class ProductValueRemovedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE_PRODUCT_VALUE = 'product_value';

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
        return $event instanceof ProductValueRemoved;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ProductValueRemoved) {
            throw new UnsupportedEventException($event, ProductValueRemoved::class);
        }

        $this->connection->beginTransaction();
        try {
            $this->delete($aggregateId->getValue(), AttributeId::fromKey($event->getAttributeCode())->getValue());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }

    /**
     * @param string $productId
     * @param string $attributeId
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    private function delete(string $productId, string $attributeId): void
    {
        $this->connection->delete(
            self::TABLE_PRODUCT_VALUE,
            [
                'product_id' => $productId,
                'attribute_id' => $attributeId,
            ]
        );
    }
}
