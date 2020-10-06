<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Reader;

use Ergonode\Importer\Domain\Entity\Import;
use ZipArchive;

final class ErgonodeZipExtractor
{
    /**
     * @var string
     */
    private string $sourceDirectory;

    /**
     * @var string
     */
    private string $destinationDirectory;

    /**
     * @param string $sourceDirectory
     * @param string $destinationDirectory
     */
    public function __construct(string $sourceDirectory, string $destinationDirectory)
    {
        $this->sourceDirectory = $sourceDirectory;
        $this->destinationDirectory = $destinationDirectory;
    }

    /**
     * @param Import $import
     */
    public function extract(Import $import): string
    {
        $file = "{$this->sourceDirectory}{$import->getFile()}";

        $archive = new ZipArchive();
        $resource = $archive->open($file);

        if (true !== $resource) {
            throw new \Exception("Can't open file \"$file\"");
        }

        $result = $archive->extractTo("{$this->destinationDirectory}{$import->getFile()}");

        if (!$result) {
            throw new \Exception("Can't extract files from ZIP file \"$file\"");
        }

        $archive->close();

        return "{$this->destinationDirectory}{$file}";
    }
}