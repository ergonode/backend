<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Service;

use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;

class ImporterFileRemover
{
    private FilesystemInterface $importStorage;

    private LoggerInterface $logger;

    public function __construct(
        FilesystemInterface $importStorage,
        LoggerInterface $logger
    ) {
        $this->importStorage = $importStorage;
        $this->logger = $logger;
    }

    public function remove(string $fileName): bool
    {
        if ($this->importStorage->has($fileName)) {
            try {
                $this->importStorage->delete($fileName);
            } catch (\Exception $exception) {
                $this->logger->error($exception);
            }
        }

        return false;
    }
}
