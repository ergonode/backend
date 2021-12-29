<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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

        $this->updateMultimediaWithSlash($nameEventId, $createEventId);

        $this->updateMultimediaWithDuplicatedNames($nameEventId, $createEventId);
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
    private function getIdsWithSlash(): array
    {
        return $this->connection
            ->executeQuery("SELECT m.id, m.name, m.extension FROM multimedia m WHERE m.name ILIKE '%/%'")
            ->fetchAllAssociativeIndexed();
    }

    private function getIdsWithDuplicatedNames(): array
    {
        return $this->connection
            ->executeQuery(
                '
                SELECT m.id, m.name, m.extension FROM multimedia m
                WHERE
                      m."name" IN(
                          SELECT m2."name" FROM multimedia m2
                          GROUP BY m2."name"
                          HAVING count(m2.id) >1 
                      ) 
                ORDER BY m.created_at ASC'
            )
            ->fetchAllAssociativeIndexed();
    }

    private function generateNameWithoutSlash(string $id, string $filename, string $extension): string
    {
        $newName = $filename = str_replace('/', '_', $filename);
        $i = 0;
        while ($this->fileExists($id, $newName)) {
            $newName = $this->generateSuffix($filename, $extension, $i++);
        }

        return $newName;
    }

    private function generateNameWithoutDuplicates(string $id, string $name, string $extension): string
    {
        $newName = $name;
        $i = 1;
        while ($this->fileExists($id, $newName)) {
            $newName = $this->generateSuffix($name, $extension, $i++);
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

    private function generateSuffix(string $filename, string $extension, int $iterationIndex): string
    {
        $name = $filename;
        $extensionToAppend = '';
        if (!empty($extension) && str_ends_with($filename, $extension)) {
            $extensionToAppend = '.'.$extension;
            $name = substr($filename, 0, -(strlen($extension) + 1));
        }
        $suffix = '('.$iterationIndex.')';
        if (mb_strlen($filename) > (self::MAX_LENGTH - mb_strlen($suffix))) {
            return mb_substr(
                $name,
                0,
                self::MAX_LENGTH - mb_strlen($suffix)-mb_strlen($extensionToAppend)
            ).$suffix.$extensionToAppend;
        }

        return $name.$suffix.$extensionToAppend;
    }

    private function updateMultimediaWithSlash(string $nameEventId, string $createEventId): void
    {
        foreach ($this->getIdsWithSlash() as $id => $data) {
            $oldName = $data['name'];
            $extension = $data['extension'];

            $newName = $this->generateNameWithoutSlash($id, $oldName, $extension);
            $this->updateProjection($id, $newName);
            $this->updateEvent($id, $nameEventId, $oldName, $newName);
            $this->updateEvent($id, $createEventId, $oldName, $newName);
            $this->clearSnapshot($id);
        }
    }

    private function updateMultimediaWithDuplicatedNames(string $nameEventId, string $createEventId): void
    {
        foreach ($this->getIdsWithDuplicatedNames() as $id => $data) {
            $oldName = $data['name'];
            $extension = $data['extension'];

            $newName = $this->generateNameWithoutDuplicates($id, $oldName, $extension);
            $this->updateProjection($id, $newName);
            $this->updateEvent($id, $nameEventId, $oldName, $newName);
            $this->updateEvent($id, $createEventId, $oldName, $newName);
            $this->clearSnapshot($id);
        }
    }
}
