<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateDefaultImageRemovedEvent;

class DbalTemplateDefaultImageRemovedEventProjector
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
    public function __invoke(TemplateDefaultImageRemovedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'default_image' => null,
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
