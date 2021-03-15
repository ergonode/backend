<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateElementChangedEvent;

class DbalTemplateElementChangedEventProjector
{
    private const ELEMENT_TABLE = 'designer.template_element';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(TemplateElementChangedEvent $event): void
    {
        $element = $event->getElement();
        $this->connection->update(
            self::ELEMENT_TABLE,
            [
                'width' => $element->getSize()->getWidth(),
                'height' => $element->getSize()->getHeight(),
                'type' => $element->getType(),
            ],
            [
                'template_id' => $event->getAggregateId()->getValue(),
                'x' => $element->getPosition()->getX(),
                'y' => $element->getPosition()->getY(),
            ]
        );
    }
}
