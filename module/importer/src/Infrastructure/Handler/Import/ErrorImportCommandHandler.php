<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\ErrorImportCommand;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class ErrorImportCommandHandler
{
    /**
     * @var ImportLineRepositoryInterface
     */
    private ImportLineRepositoryInterface $repository;

    /**
     * @param ImportLineRepositoryInterface $repository
     */
    public function __construct(ImportLineRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ErrorImportCommand $command
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(ErrorImportCommand $command)
    {
        $process = $this->repository->getLine($command->getId(), $command->getLine());

        Assert::notNull($process);

        $process->end();
        $this->repository->save($process);
    }
}
