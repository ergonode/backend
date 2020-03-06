<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Option;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\AttributeOptionRemovedEvent;

/**
 */
class AttributeOptionRemovedEventProjector
{
    private const TABLE_ATTRIBUTE_OPTION = 'attribute_option';

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
     * @param AttributeOptionRemovedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(AttributeOptionRemovedEvent $event): void
    {
        $this->delete($event->getKey()->getValue(), $event->getAggregateId()->getValue());
    }

    /**
     * @param string $key
     * @param string $attributeId
     *
     * @throws DBALException
     */
    private function delete(string $key, string $attributeId): void
    {
        $this->connection->delete(
            self::TABLE_ATTRIBUTE_OPTION,
            [
                'attribute_id' => $attributeId,
                'key' => $key,
            ]
        );
    }
}
