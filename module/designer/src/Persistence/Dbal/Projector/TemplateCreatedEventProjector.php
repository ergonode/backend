<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateCreatedEvent;

/**
 */
class TemplateCreatedEventProjector
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
    public function __invoke(TemplateCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'name' => $event->getName(),
                'default_text' => $event->getDefaultText() ? $event->getDefaultText()->getValue() : null,
                'default_image' => $event->getDefaultImage() ? $event->getDefaultImage()->getValue() : null,
                'image_id' => $event->getImageId() ? $event->getImageId()->getValue() : null,
                'template_group_id' => $event->getGroupId()->getValue(),
            ]
        );
    }
}
