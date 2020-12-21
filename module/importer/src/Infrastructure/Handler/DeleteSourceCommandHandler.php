<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\DeleteSourceCommand;
use Ergonode\Importer\Domain\Query\ImportQueryInterface;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

class DeleteSourceCommandHandler
{
    private SourceRepositoryInterface $sourceRepository;

    private ImportQueryInterface $importQuery;

    private FilesystemInterface $importStorage;

    private LoggerInterface $logger;

    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        ImportQueryInterface $importQuery,
        FilesystemInterface $importStorage,
        LoggerInterface $logger
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->importQuery = $importQuery;
        $this->importStorage = $importStorage;
        $this->logger = $logger;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteSourceCommand $command): void
    {
        $source = $this->sourceRepository->load($command->getId());

        Assert::notNull($source, sprintf('Can\'t fid source "%s"', $command->getId()->getValue()));

        $fileNames = $this->importQuery->getFileNamesBySourceId($source->getId());

        $this->sourceRepository->delete($source);

        foreach ($fileNames as $fileName) {
            if ($this->importStorage->has($fileName)) {
                try {
                    $this->importStorage->delete($fileName);
                } catch (\Exception $exception) {
                    $this->logger->error($exception);
                }
            }
        }
    }
}
