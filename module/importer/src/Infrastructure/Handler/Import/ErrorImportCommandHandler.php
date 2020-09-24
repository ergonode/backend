<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\ErrorImportCommand;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Webmozart\Assert\Assert;
use Doctrine\DBAL\DBALException;

/**
 */
class ErrorImportCommandHandler
{
    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @param ImportErrorRepositoryInterface $repository
     */
    public function __construct(ImportErrorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ErrorImportCommand $command
     *
     * @throws DBALException
     */
    public function __invoke(ErrorImportCommand $command)
    {
        $line = $this->repository->load($command->getId(), $command->getSteps()->getPosition(), $command->getLine());

        Assert::notNull($line);

        $line->addError($command->getMessage());
        $this->repository->save($line);
    }
}
