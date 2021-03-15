<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateDefaultImageChangedEvent;

class DbalTemplateDefaultImageChangedEventProjector
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
    public function __invoke(TemplateDefaultImageChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'default_image' => $event->getTo()->getValue(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
