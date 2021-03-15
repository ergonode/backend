<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector\Group;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\Group\TemplateGroupCreatedEvent;

class DbalTemplateGroupCreatedEventProjector
{
    private const TABLE = 'designer.template';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(TemplateGroupCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'name' => $event->getName(),
            ]
        );
    }
}
