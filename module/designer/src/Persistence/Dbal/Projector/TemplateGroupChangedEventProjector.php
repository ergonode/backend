<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateGroupChangedEvent;

/**
 */
class TemplateGroupChangedEventProjector
{
    private const TABLE = 'designer.template';

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
     * {@inheritDoc}
     */
    public function __invoke(TemplateGroupChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'template_group_id' => $event->getNew()->getValue(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
