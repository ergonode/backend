<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Product\Domain\Event\ProductRemovedFromCategory;

/**
 */
class ProductRemovedFromCategoryEventProjector implements DomainEventProjectorInterface
{
    private const TABLE_PRODUCT_CATEGORY = 'product_category_product';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * ProductCreateEventProjector constructor.
     *
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
        return $event instanceof ProductRemovedFromCategory;
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
        if (!$event instanceof ProductRemovedFromCategory) {
            throw new UnsupportedEventException($event, ProductRemovedFromCategory::class);
        }

        $this->connection->beginTransaction();

        try {
            $this->connection->delete(
                self::TABLE_PRODUCT_CATEGORY,
                [
                    'product_id' => $aggregateId->getValue(),
                    'category_id' => CategoryId::fromCode($event->getCategoryCode()),
                ]
            );
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }
}
