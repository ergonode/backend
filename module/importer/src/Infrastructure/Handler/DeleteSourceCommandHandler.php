<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\DeleteSourceCommand;
use Ergonode\Importer\Domain\Command\ImportDeletedCommand;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Domain\Query\ImportQueryInterface;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Webmozart\Assert\Assert;

class DeleteSourceCommandHandler
{
    private SourceRepositoryInterface $sourceRepository;

    private ImportQueryInterface $importQuery;

    private CommandBusInterface $commandBus;

    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        ImportQueryInterface $importQuery,
        CommandBusInterface $commandBus
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->importQuery = $importQuery;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteSourceCommand $command): void
    {
        $source = $this->sourceRepository->load($command->getId());

        Assert::isInstanceOf(
            $source,
            AbstractSource::class,
            sprintf('Can\'t find source "%s"', $command->getId()->getValue())
        );

        $fileNames = $this->importQuery->getFileNamesBySourceId($source->getId());

        $this->sourceRepository->delete($source);

        foreach ($fileNames as $fileName) {
            $importDeletedCommand = new ImportDeletedCommand($fileName, $source->getType());
            $this->commandBus->dispatch($importDeletedCommand);
        }
    }
}
