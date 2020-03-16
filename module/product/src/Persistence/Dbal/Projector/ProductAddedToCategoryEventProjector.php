<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Product\Domain\Event\ProductAddedToCategoryEvent;

/**
 */
class ProductAddedToCategoryEventProjector
{
    private const TABLE_PRODUCT_CATEGORY = 'product_category_product';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductAddedToCategoryEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductAddedToCategoryEvent $event): void
    {
        $this->connection->insert(
            self::TABLE_PRODUCT_CATEGORY,
            [
                'product_id' => $event->getAggregateId()->getValue(),
                'category_id' => $event->getCategoryId()->getValue(),
            ]
        );
    }
}
