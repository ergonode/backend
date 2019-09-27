<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Product\Domain\Event\ProductAddedToCategory;

/**
 */
class ProductAddedToCategoryEventProjector implements DomainEventProjectorInterface
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
     * {@inheritDoc}
     */
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof ProductAddedToCategory;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, ProductAddedToCategory::class);
        }

        $this->connection->insert(
            self::TABLE_PRODUCT_CATEGORY,
            [
                'product_id' => $aggregateId->getValue(),
                'category_id' => CategoryId::fromCode($event->getCategoryCode()),
            ]
        );
    }
}
