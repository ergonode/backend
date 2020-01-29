<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\StartImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Service\Import\StartImportService;
use Webmozart\Assert\Assert;

/**
 */
class StartImportCommandHandler
{
    /**
     * @var ImportRepositoryInterface
     */
    private ImportRepositoryInterface $repository;

    /**
     * @var StartImportService
     */
    private $service;

    /**
     * @param ImportRepositoryInterface $repository
     * @param StartImportService        $service
     */
    public function __construct(ImportRepositoryInterface $repository, StartImportService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * @param StartImportCommand $command
     *
     * @throws \ReflectionException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(StartImportCommand $command)
    {
        $import = $this->repository->load($command->getId());

        Assert::notNull($import);
        Assert::isInstanceOf($import, Import::class);

        $import->start();

        $this->service->start($import);

        $this->repository->save($import);
    }
}
