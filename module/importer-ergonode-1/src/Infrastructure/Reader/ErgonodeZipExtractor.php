<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\Exception\ErgonodeZipExtractorException;
use League\Flysystem\FilesystemInterface;
use ZipArchive;

class ErgonodeZipExtractor
{
    private string $directory;
    private FilesystemInterface $importStorage;


    public function __construct(FilesystemInterface $importStorage)
    {
        /** @phpstan-ignore-next-line */
        $this->directory = $importStorage->getAdapter()->getPathPrefix();
        $this->importStorage = $importStorage;
    }

    /**
     * @throws ErgonodeZipExtractorException
     */
    public function extract(Import $import): string
    {
        $file = "{$this->directory}{$import->getFile()}";

        $archive = new ZipArchive();
        $resource = $archive->open($file);

        if (true !== $resource) {
            throw new ErgonodeZipExtractorException("Can't open file \"$file\"");
        }

        $extractDirectory = $this->directory.$import->getFileHash();
        $this->importStorage->createDir($import->getFileHash());
        @chmod($extractDirectory, 0766);
        $result = $archive->extractTo($extractDirectory);

        if (!$result) {
            throw new ErgonodeZipExtractorException(
                "Can't extract files from ZIP file \"$file\" into \"$extractDirectory\""
            );
        }

        $archive->close();

        return $extractDirectory;
    }

    /**
     * @throws ErgonodeZipExtractorException
     */
    public function cleanup(Import $import): void
    {
        if (!$this->importStorage->deleteDir($import->getFileHash())) {
            throw new ErgonodeZipExtractorException(sprintf('Can\'t remove "%s" directory', $import->getFileHash()));
        }
    }
}
