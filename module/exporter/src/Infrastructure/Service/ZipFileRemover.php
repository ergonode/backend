<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Exporter\Infrastructure\Service;

use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;

class ZipFileRemover implements FileRemoverInterface
{
    private FilesystemInterface $exportStorage;

    private LoggerInterface $logger;

    public function __construct(
        FilesystemInterface $exportStorage,
        LoggerInterface $logger
    ) {
        $this->exportStorage = $exportStorage;
        $this->logger = $logger;
    }

    public function remove(string $fileName): bool
    {
        $file = sprintf('%s.zip', $fileName);
        if ($this->exportStorage->has($file)) {
            try {
                return $this->exportStorage->delete($file);
            } catch (\Exception $exception) {
                $this->logger->error($exception);
            }
        }

        return false;
    }
}
