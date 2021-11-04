<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Doctrine\DBAL\Connection;

abstract class AbstractDbalAttributeOptionEventProjector
{
    protected const TABLE = 'attribute_options';

    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function getPosition(AttributeId $attributeId, ?AggregateId $positionId = null): int
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('max(index)')
            ->from(self::TABLE)
            ->where($qb->expr()->eq('attribute_id', ':attributeId'))
            ->setParameter(':attributeId', $attributeId->getValue());

        if ($positionId) {
            $qb->andWhere($qb->expr()->eq('option_id', ':optionId'))
                ->setParameter('optionId', $positionId->getValue());
        }

        return (int) $qb->execute()->fetchOne();
    }

    protected function shiftPosition(AttributeId $attributeId, int $index): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->update(self::TABLE)
            ->set('index', 'index + 1')
            ->where($qb->expr()->eq('attribute_id', ':attributeId'))
            ->andWhere($qb->expr()->gte('index', ':index'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->setParameter(':index', $index)
            ->execute();
    }

    protected function mergePosition(AttributeId $attributeId, int $index): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->update(self::TABLE)
            ->set('index', 'index - 1')
            ->where($qb->expr()->eq('attribute_id', ':attributeId'))
            ->andWhere($qb->expr()->gte('index', ':index'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->setParameter(':index', $index)
            ->execute();
    }
}
