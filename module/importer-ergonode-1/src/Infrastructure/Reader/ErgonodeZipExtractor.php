<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Reader;

use Ergonode\Importer\Domain\Entity\Import;
use League\Flysystem\FilesystemInterface;
use ZipArchive;

/**
 */
final class ErgonodeZipExtractor
{
    /**
     * @var string
     */
    private string $directory;

    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $importStorage;

    /**
     * @param FilesystemInterface $importStorage
     */
    public function __construct(FilesystemInterface $importStorage)
    {
        $this->directory = $importStorage->getAdapter()->getPathPrefix();
        $this->importStorage = $importStorage;
    }

    /**
     * @param Import $import
     *
     * @return string
     *
     * @throws \Exception
     */
    public function extract(Import $import): string
    {
        $file = "{$this->directory}{$import->getFile()}";

        $archive = new ZipArchive();
        $resource = $archive->open($file);

        if (true !== $resource) {
            throw new \Exception("Can't open file \"$file\"");
        }

        $extractDirectory = $this->directory.$import->getFileHash();
        $this->importStorage->createDir($import->getFileHash());
        $result = $archive->extractTo($extractDirectory);

        if (!$result) {
            throw new \Exception("Can't extract files from ZIP file \"$file\" into \"$extractDirectory\"");
        }

        $archive->close();

        return $extractDirectory;
    }

    /**
     * @param Import $import
     */
    public function cleanup(Import $import): void
    {
        if (!$this->importStorage->deleteDir($import->getFileHash())) {
            throw new \RuntimeException(sprintf('Can\'t remove "%s" directory', $import->getFileHash()));
        }
    }
}
