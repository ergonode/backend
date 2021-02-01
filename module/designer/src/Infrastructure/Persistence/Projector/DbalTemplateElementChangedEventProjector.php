<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateElementChangedEvent;
use Ergonode\Core\Application\Serializer\SerializerInterface;

class DbalTemplateElementChangedEventProjector
{
    private const ELEMENT_TABLE = 'designer.template_element';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function __invoke(TemplateElementChangedEvent $event): void
    {
        $element = $event->getElement();
        $this->connection->update(
            self::ELEMENT_TABLE,
            [
                'width' => $element->getSize()->getWidth(),
                'height' => $element->getSize()->getHeight(),
                'properties' => $this->serializer->serialize($element->getProperties()),
            ],
            [
                'template_id' => $event->getAggregateId()->getValue(),
                'x' => $element->getPosition()->getX(),
                'y' => $element->getPosition()->getY(),
            ]
        );
    }
}
