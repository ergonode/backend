<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Service;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
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

    public function remove(ExportId $exportId): bool
    {
        $file = sprintf('%s.zip', $exportId->getValue());
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
