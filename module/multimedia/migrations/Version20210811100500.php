<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210811100500 extends AbstractErgonodeMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function up(Schema $schema): void
    {
        /** @var FilesystemInterface $multimediaStorage */
        $multimediaStorage = $this->container
            ->get('multimedia.storage');

        $data = $this->getHash();

        foreach ($data as $row) {
            try {
                $this->migrateFile(
                    $multimediaStorage,
                    new MultimediaId($row['id']),
                    new Hash($row['hash']),
                    $row['extension'],
                );
            } catch (\Exception $exception) {
                $this->write($exception->getMessage());
            }
        }
    }

    private function getHash(): array
    {
        return $this->connection
            ->executeQuery(
                '
                SELECT m.id, m.hash, m.extension FROM multimedia m         
                ORDER BY m.created_at ASC'
            )
            ->fetchAllAssociative();
    }

    private function migrateFile(
        FilesystemInterface $multimediaStorage,
        MultimediaId $multimediaId,
        Hash $hash,
        string $extension
    ): void {
        $oldFilename = sprintf('%s.%s', $hash->getValue(), $extension);
        $newFilename = sprintf('%s.%s', $multimediaId->getValue(), $extension);

        if (!$multimediaStorage->has($oldFilename)) {
            return;
        }

        $multimediaStorage->rename($oldFilename, $newFilename);
    }
}
