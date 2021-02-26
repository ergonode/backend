<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Application\Serializer\SerializerInterface;

abstract class AbstractDbalAttributeParameterChangeEventProjector
{
    private const TABLE_PARAMETER = 'attribute_parameter';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $value
     *
     * @throws DBALException
     */
    protected function projection(AttributeId $attributeId, string $name, $value): void
    {
        if (null !== $value) {
            $this->connection->update(
                self::TABLE_PARAMETER,
                [
                    'value' => $this->serializer->serialize($value),
                ],
                [
                    'attribute_id' => $attributeId->getValue(),
                    'type' => $name,
                ]
            );
        }
    }
}
