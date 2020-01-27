<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Source;

use Ergonode\Importer\Domain\Command\Source\CreateFileImportCommand;
use Ergonode\Importer\Domain\Entity\FileImport;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;

/**
 */
class CreateFileImportCommandHandler
{
    /**
     * @var ImportRepositoryInterface
     */
    private ImportRepositoryInterface $repository;

    /**
     * @param ImportRepositoryInterface $repository
     */
    public function __construct(ImportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateFileImportCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateFileImportCommand $command)
    {
        $import = new FileImport(
            $command->getId(),
            $command->getName(),
            $command->getFilename(),
            $command->getSourceType(),
        );

        $this->repository->save($import);
    }
}
