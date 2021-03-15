<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateElementAddedEvent;

class DbalTemplateElementAddedEventProjector
{
    private const ELEMENT_TABLE = 'designer.template_element';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(TemplateElementAddedEvent $event): void
    {
        $element = $event->getElement();
        $this->connection->insert(
            self::ELEMENT_TABLE,
            [
                'template_id' => $event->getAggregateId()->getValue(),
                'x' => $element->getPosition()->getX(),
                'y' => $element->getPosition()->getY(),
                'type' => $element->getType(),
                'width' => $element->getSize()->getWidth(),
                'height' => $element->getSize()->getHeight(),
            ]
        );
    }
}
