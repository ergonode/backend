<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\SerializerInterface;

/**
 */
abstract class AbstractAttributeParameterChangeEventProjector
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
     * @param AttributeId $attributeId
     * @param string      $name
     * @param mixed       $value
     *
     * @throws DBALException
     */
    protected function projection(AttributeId $attributeId, string $name, $value): void
    {
        if (null !== $value) {
            $this->connection->update(
                self::TABLE_PARAMETER,
                [
                    'value' => $this->serializer->serialize($value, 'json'),
                ],
                [
                    'attribute_id' => $attributeId->getValue(),
                    'type' => $name,
                ]
            );
        }
    }
}
