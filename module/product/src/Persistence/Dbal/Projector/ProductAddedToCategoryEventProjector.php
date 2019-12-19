<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Product\Domain\Event\ProductAddedToCategory;

/**
 */
class ProductAddedToCategoryEventProjector
{
    private const TABLE_PRODUCT_CATEGORY = 'product_category_product';

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
     * @param ProductAddedToCategory $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductAddedToCategory $event): void
    {
        $this->connection->insert(
            self::TABLE_PRODUCT_CATEGORY,
            [
                'product_id' => $event->getAggregateId()->getValue(),
                'category_id' => CategoryId::fromCode($event->getCategoryCode()),
            ]
        );
    }
}
