<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Service;

class ZipDirectoryCompressor implements DirectoryCompressorInterface
{
    public function compress(string $sourceDirectory, string $destinationDirectory, string $fileName): string
    {
        if (!is_dir($sourceDirectory)) {
            throw new \RuntimeException('%s is not directory', $sourceDirectory);
        }

        if (!is_dir($destinationDirectory)) {
            throw new \RuntimeException('%s is not directory', $destinationDirectory);
        }

        $fileName  = sprintf('%s.zip', $fileName);
        $filePath = sprintf('%s/%s', $destinationDirectory, $fileName);

        $zip = new \ZipArchive();
        $zip->open($filePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDirectory),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($sourceDirectory) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        return $fileName;
    }
}
