<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\DeleteSourceCommand;
use Ergonode\Importer\Domain\Command\Import\DeleteImportCommand;
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

        $importIds = $this->importQuery->getImportIdsBySourceId($source->getId());

        foreach ($importIds as $importId) {
            $importDeletedCommand = new DeleteImportCommand($importId);
            $this->commandBus->dispatch($importDeletedCommand);
        }
        $this->sourceRepository->delete($source);
    }
}
