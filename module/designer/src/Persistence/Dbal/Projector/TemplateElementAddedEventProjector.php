<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Event\TemplateElementAddedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class TemplateElementAddedEventProjector
{
    private const ELEMENT_TABLE = 'designer.template_element';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
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
                'width' => $element->getSize()->getWidth(),
                'height' => $element->getSize()->getHeight(),
                'properties' => $this->serializer->serialize($element->getProperties(), 'json'),
            ]
        );
    }
}
