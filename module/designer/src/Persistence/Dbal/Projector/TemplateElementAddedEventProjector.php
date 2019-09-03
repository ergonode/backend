<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Event\TemplateElementAddedEvent;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use JMS\Serializer\SerializerInterface;

/**
 */
class TemplateElementAddedEventProjector implements DomainEventProjectorInterface
{
    private const ELEMENT_TABLE = 'designer.template_element';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

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
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof TemplateElementAddedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof TemplateElementAddedEvent) {
            throw new UnsupportedEventException($event, TemplateElementAddedEvent::class);
        }

        $element = $event->getElement();
        $this->connection->insert(
            self::ELEMENT_TABLE,
            [
                'template_id' => $aggregateId->getValue(),
                'x' => $element->getPosition()->getX(),
                'y' => $element->getPosition()->getY(),
                'width' => $element->getSize()->getWidth(),
                'height' => $element->getSize()->getHeight(),
                'properties' => $this->serializer->serialize($element->getProperties(), 'json'),
            ]
        );
    }
}
