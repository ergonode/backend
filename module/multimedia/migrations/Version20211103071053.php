<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;
use Ergonode\Multimedia\Domain\Event\MultimediaDeletedEvent;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20211103071053 extends AbstractErgonodeMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function up(Schema $schema): void
    {
        /** @var FilesystemInterface $multimediaStorage */
        $multimediaStorage = $this->container
            ->get('multimedia.storage');

        /** @var FilesystemInterface $thumbnailStorage */
        $thumbnailStorage = $this->container
            ->get('thumbnail.storage');

        $data = $this->getData();

        foreach ($data as $row) {
            try {
                $multimediaId = new MultimediaId($row['id']);
                $hash = new Hash($row['hash']);

                $this->delete($multimediaStorage, $multimediaId, $hash, $row['extension']);
                $this->deleteThumbnail($thumbnailStorage, $multimediaId, $hash, 'default', 'png');
            } catch (\Exception $exception) {
                $this->write($exception->getMessage());
            }
        }
    }

    private function delete(
        FilesystemInterface $storage,
        MultimediaId $multimediaId,
        Hash $hash,
        string $extension
    ): void {
        $filename = sprintf('%s.%s', $multimediaId->getValue(), $extension);
        $oldFilename = sprintf('%s.%s', $hash->getValue(), $extension);

        if ($storage->has($filename)) {
            $storage->delete($filename);
        }

        if ($storage->has($oldFilename)) {
            $storage->delete($oldFilename);
        }
    }

    private function deleteThumbnail(
        FilesystemInterface $storage,
        MultimediaId $multimediaId,
        Hash $hash,
        string $thumbnail,
        string $extension
    ): void {
        $filename = sprintf('%s/%s.%s', $thumbnail, $multimediaId->getValue(), $extension);
        $oldFilename = sprintf('%s/%s.%s', $thumbnail, $hash->getValue(), $extension);

        if ($storage->has($filename)) {
            $storage->delete($filename);
        }

        if ($storage->has($oldFilename)) {
            $storage->delete($oldFilename);
        }
    }

    /**
     * @return mixed[]
     */
    private function getData(): array
    {
        return $this->connection->executeQuery(
            'SELECT
                esh.aggregate_id AS id,
                ec.hash,
                ec.extension
            FROM event_store_history esh
            LEFT JOIN event_store_event ese ON ese.id = esh.event_id
            LEFT JOIN (
                SELECT
                    eshc.aggregate_id,
                    eshc.payload->>\'hash\' AS hash,
                    eshc.payload->>\'extension\' AS extension
                FROM event_store_history eshc
                LEFT JOIN event_store_event esec on esec.id = eshc.event_id
                WHERE
                    esec.event_class = \':createClass\'
            ) ec ON ec.aggregate_id = esh.aggregate_id
            WHERE
                ese.event_class = \':deleteClass\'',
            [
                'createClass' => MultimediaCreatedEvent::class,
                'deleteClass' => MultimediaDeletedEvent::class,
            ]
        )->fetchAllAssociative();
    }
}
