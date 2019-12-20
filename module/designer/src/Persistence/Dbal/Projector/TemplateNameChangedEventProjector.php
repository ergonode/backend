<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateNameChangedEvent;

/**
 */
class TemplateNameChangedEventProjector
{
    private const TABLE = 'designer.template';

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
    public function __invoke(TemplateNameChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'name' => $event->getTo(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
