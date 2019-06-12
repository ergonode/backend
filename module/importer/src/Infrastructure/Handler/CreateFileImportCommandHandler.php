<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Application\Service\ImportService;
use Ergonode\Importer\Domain\Command\CreateFileImportCommand;
use Ergonode\Importer\Domain\Entity\FileImport;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;

/**
 */
class CreateFileImportCommandHandler
{
    /**
     * @var ImportService
     */
    private $service;

    /**
     * @var ImportRepositoryInterface
     */
    private $repository;

    /**
     * @param ImportService             $service
     * @param ImportRepositoryInterface $repository
     */
    public function __construct(ImportService $service, ImportRepositoryInterface $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    /**
     * @param CreateFileImportCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateFileImportCommand $command)
    {
        $import = new FileImport($command->getId(), $command->getName(), $command->getReaderId(), $command->getFilename());
        $this->repository->save($import);
        $this->service->import($import, $command->getTransformerId(), $command->getAction());
    }
}
