<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeHintChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ramsey\Uuid\Uuid;

class DbalAttributeHintChangedEventProjector
{
    private const TABLE = 'value_translation';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(AttributeHintChangedEvent $event): void
    {
        $to = $event->getTo()->getTranslations();
        $aggregateId = $event->getAggregateId();

        $this->connection->delete(
            self::TABLE,
            [
                'value_id' => $this->getTranslationId('hint', $aggregateId),
            ]
        );

        foreach ($to as $language => $value) {
            $this->connection->insert(
                self::TABLE,
                [
                    'id' => Uuid::uuid4()->toString(),
                    'value_id' => $this->getTranslationId('hint', $aggregateId),
                    'language' => $language,
                    'value' => $value,
                ]
            );
        }
    }

    private function getTranslationId(string $field, AttributeId $attributeId): string
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select($field)
            ->from('attribute')
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $attributeId->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);
    }
}
