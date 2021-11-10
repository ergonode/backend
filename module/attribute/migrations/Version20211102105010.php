<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionAddedEvent;
use Ergonode\Attribute\Domain\Event\Option\OptionCreatedEvent;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20211102105010 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $eventId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => AttributeOptionAddedEvent::class,
            ]
        )->fetchOne();

        $recordedAt = new \DateTime('now');

        $data = $this->getRows();

        foreach ($data as $row) {
            $payload = json_encode(
                [
                    "id" => $row['attribute_id'],
                    "optionId" => $row['aggregate_id'],
                    "index" => $row['row_number'] - 1,
                ],
                JSON_UNESCAPED_UNICODE
            );

            $sequence = $this->getMaxSequence($row['aggregate_id']);
            $this->insertEvent($row['aggregate_id'], $sequence + 1, $eventId, $payload, $recordedAt);
            $this->clearSnapshot($row['aggregate_id']);
        }
    }

    private function getMaxSequence(string $aggregateId): int
    {
        $max = $this->connection->executeQuery(
            'SELECT MAX("sequence") FROM event_store WHERE aggregate_id = :aggregateId',
            [
                'aggregateId' => $aggregateId,
            ]
        )->fetchOne();

        return $max ?: 0;
    }

    private function insertEvent(
        string $optionId,
        int $sequence,
        string $eventId,
        string $payload,
        \DateTime $recordedAt
    ): void {
        $this->addSql(
            'INSERT INTO event_store (aggregate_id, sequence, event_id, payload, recorded_at) VALUES (?,?,?,?,?)',
            [
                $optionId,
                $sequence,
                $eventId,
                $payload,
                $recordedAt->format('Y-m-d H:i:s.u'),
            ]
        );
    }

    private function clearSnapshot(string $id): void
    {
        $this->connection->executeQuery(
            'DELETE FROM event_store_snapshot WHERE aggregate_id = :id',
            [
                'id' => $id,
            ],
        );
    }

    private function getRows(): array
    {
        return $this->connection
            ->executeQuery(
                '
                SELECT 
                    es.aggregate_id,
                    es.payload->>\'attribute_id\' as attribute_id,
                    ROW_NUMBER() OVER (PARTITION BY es.payload->>\'attribute_id\' ORDER BY es.id)
                FROM event_store es 
                JOIN event_store_event ese ON ese.id = es.event_id 
                WHERE ese.event_class = :class
                ORDER BY attribute_id ASC, es.id ASC
                ',
                [
                    'class' => OptionCreatedEvent::class,
                ]
            )
            ->fetchAllAssociative();
    }
}
