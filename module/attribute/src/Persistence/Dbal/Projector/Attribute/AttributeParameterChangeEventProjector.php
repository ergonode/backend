<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class AttributeParameterChangeEventProjector
{
    private const TABLE_PARAMETER = 'attribute_parameter';

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
     * @param AttributeParameterChangeEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(AttributeParameterChangeEvent $event): void
    {
        if (!empty($event->getTo())) {
            $this->connection->update(
                self::TABLE_PARAMETER,
                [
                    'value' => $this->serializer->serialize($event->getTo(), 'json'),
                ],
                [
                    'attribute_id' => $event->getAggregateId()->getValue(),
                    'type' => $event->getName(),
                ]
            );
        }
    }
}
