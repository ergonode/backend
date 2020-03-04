<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Product\Domain\Event\ProductValueRemovedEvent;

/**
 */
class ProductValueRemovedEventProjector
{
    private const TABLE_PRODUCT_VALUE = 'product_value';

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
     * @param ProductValueRemovedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductValueRemovedEvent $event): void
    {
        $this
            ->delete(
                $event->getAggregateId()->getValue(),
                AttributeId::fromKey($event->getAttributeCode()->getValue())->getValue()
            );
    }

    /**
     * @param string $productId
     * @param string $attributeId
     *
     * @throws DBALException
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
