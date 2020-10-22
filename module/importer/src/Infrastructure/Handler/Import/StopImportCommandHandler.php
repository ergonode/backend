<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\StopImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Webmozart\Assert\Assert;

class StopImportCommandHandler
{
    private ImportRepositoryInterface $repository;

    public function __construct(ImportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \ReflectionException
     */
    public function __invoke(StopImportCommand $command)
    {
        $import = $this->repository->load($command->getId());

        Assert::isInstanceOf($import, Import::class);

        $import->stop();
        $this->repository->save($import);
        $this->repository->addError($import->getId(), $command->getReason());
    }
}
