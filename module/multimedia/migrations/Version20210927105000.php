<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Ergonode\Multimedia\Domain\Event\MultimediaNameChangedEvent;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210927105000 extends AbstractErgonodeMigration implements ContainerAwareInterface
{
    private const MAX_LENGTH = 128;

    use ContainerAwareTrait;

    public function up(Schema $schema): void
    {
        $nameEventId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => MultimediaNameChangedEvent::class,
            ]
        )->fetchOne();

        $createEventId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => MultimediaCreatedEvent::class,
            ]
        )->fetchOne();

        foreach ($this->getIds() as $id => $data) {
            $oldName = $data['name'];

            $newName = $this->generateName($id, $oldName);
            $this->updateProjection($id, $newName);
            $this->updateEvent($id, $nameEventId, $oldName, $newName);
            $this->updateEvent($id, $createEventId, $oldName, $newName);
            $this->clearSnapshot($id);
        }
    }

    private function updateProjection(string $id, string $name): void
    {
        $this->connection->executeQuery(
            'UPDATE multimedia SET name = :name WHERE id = :id',
            [
                'id' => $id,
                'name' => $name,
            ]
        );
    }

    private function updateEvent(string $id, string $eventId, string $oldName, string $newName): void
    {
        $this->connection->executeQuery(
            'UPDATE event_store SET payload = jsonb_set(payload, \'{name}\', :newName) 
                     WHERE aggregate_id = :id AND event_id = :event ',
            [
                'oldName' => $oldName,
                'newName' => json_encode($newName),
                'id' => $id,
                'event' => $eventId,
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

    /**
     * @return string[][]
     */
    private function getIds(): array
    {
        return $this->connection
            ->executeQuery("SELECT m.id, m.name FROM multimedia m WHERE m.name ILIKE '%/%'")
            ->fetchAllAssociativeIndexed();
    }

    private function generateName(string $id, string $name): string
    {
        $newName = $name = str_replace('/', '_', $name);
        $i = 0;
        while ($this->fileExists($id, $newName)) {
            $newName = $this->generateSuffix($name, $i++);
        }

        return $newName;
    }

    private function fileExists(string $id, string $name): bool
    {
        return (bool) $this->connection
            ->executeQuery(
                'SELECT id FROM multimedia WHERE name = :name AND id <> :id',
                [
                    'id' => $id,
                    'name' => $name,
                ]
            )
            ->fetchOne();
    }

    private function generateSuffix(string $name, int $iterationIndex): string
    {
        $suffix = '('.$iterationIndex.')';
        if (mb_strlen($name) > (self::MAX_LENGTH - mb_strlen($suffix))) {
            return mb_substr($name, 0, self::MAX_LENGTH - mb_strlen($suffix)).$suffix;
        }

        return $name.$suffix;
    }
}
