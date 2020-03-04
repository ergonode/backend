<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportAttribute;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeCreatedEventProjector
{
    private const TABLE_ATTRIBUTE = 'exporter.attribute';

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
     * @param AttributeCreatedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(AttributeCreatedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $attribute = new ExportAttribute(
            $id,
            $event->getCode()->getValue(),
            $event->getLabel(),
            $event->getType(),
            $event->isMultilingual(),
            $event->getParameters()
        );

        $this->connection->insert(
            self::TABLE_ATTRIBUTE,
            [
                'id' => $attribute->getId()->toString(),
                'code' => $attribute->getCode(),
                'data' => $this->serializer->serialize($attribute, 'json'),
            ]
        );
    }
}
