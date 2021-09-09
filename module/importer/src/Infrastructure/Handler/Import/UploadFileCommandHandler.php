<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\StartImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\Importer\Domain\Command\Import\UploadFileCommand;
use Webmozart\Assert\Assert;

class UploadFileCommandHandler
{
    private SourceRepositoryInterface $sourceRepository;

    private ImportRepositoryInterface $importRepository;

    private CommandBusInterface $commandBus;

    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        ImportRepositoryInterface $importRepository,
        CommandBusInterface $commandBus
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->importRepository = $importRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UploadFileCommand $command): void
    {
        $source = $this->sourceRepository->load($command->getSourceId());
        Assert::notNull($source);

        $import = new Import(
            $command->getId(),
            $command->getSourceId(),
            $command->getFileName(),
        );

        $this->importRepository->save($import);

        $this->commandBus->dispatch(new StartImportCommand($import->getId()), true);
    }
}
