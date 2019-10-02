<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use JMS\Serializer\SerializerInterface;

/**
 */
class AttributeParameterChangeEventProjector implements DomainEventProjectorInterface
{
    private const TABLE_PARAMETER = 'attribute_parameter';

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
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof AttributeParameterChangeEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, AttributeParameterChangeEvent::class);
        }

        if (!empty($event->getTo())) {
            $this->connection->update(
                self::TABLE_PARAMETER,
                [
                    'value' => $this->serializer->serialize($event->getTo(), 'json'),
                ],
                [
                    'attribute_id' => $aggregateId->getValue(),
                    'type' => $event->getName(),
                ]
            );
        }
    }
}
