<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Attribute;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeHintChangedEvent;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ramsey\Uuid\Uuid;

/**
 */
class AttributeHintChangedEventProjector
{
    private const TABLE = 'value_translation';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param AttributeHintChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(AttributeHintChangedEvent $event): void
    {
        $from = $event->getFrom()->getTranslations();
        $to = $event->getTo()->getTranslations();
        $aggregateId = $event->getAggregateId();

        foreach ($to as $language => $value) {
            $result = $this->connection->update(
                self::TABLE,
                [
                    'language' => $language,
                    'value' => $value,
                ],
                [
                    'value_id' => $this->getTranslationId('hint', $aggregateId),
                    'language' => $language,
                ]
            );
            if (!$result) {
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

        foreach ($from as $language => $value) {
            if (!isset($to[$language])) {
                $this->connection->delete(
                    self::TABLE,
                    [
                        'value_id' => $this->getTranslationId('hint', $aggregateId),
                        'language' => $language,
                    ]
                );
            }
        }
    }

    /**
     * @param string      $field
     * @param AttributeId $attributeId
     *
     * @return string
     */
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
