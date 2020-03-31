<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Option;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Option\OptionRemovedEvent;

/**
 */
class OptionRemovedEventProjector
{
    private const TABLE_ATTRIBUTE_OPTION = 'attribute_option';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

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
     * @param OptionRemovedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(OptionRemovedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE_ATTRIBUTE_OPTION,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
