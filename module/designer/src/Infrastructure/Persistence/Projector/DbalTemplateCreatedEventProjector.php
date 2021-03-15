<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateCreatedEvent;

class DbalTemplateCreatedEventProjector
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
    public function __invoke(TemplateCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'name' => $event->getName(),
                'default_label' => $event->getDefaultLabel() ? $event->getDefaultLabel()->getValue() : null,
                'default_image' => $event->getDefaultImage() ? $event->getDefaultImage()->getValue() : null,
                'image_id' => $event->getImageId() ? $event->getImageId()->getValue() : null,
                'template_group_id' => $event->getGroupId()->getValue(),
            ]
        );
    }
}
