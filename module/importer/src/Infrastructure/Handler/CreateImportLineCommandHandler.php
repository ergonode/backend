<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\CreateImportLineCommand;
use Ergonode\Importer\Domain\Entity\ImportLine;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;

/**
 */
class CreateImportLineCommandHandler
{
    /**
     * @var ImportLineRepositoryInterface
     */
    private $repository;

    /**
     * @param ImportLineRepositoryInterface $repository
     */
    public function __construct(ImportLineRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateImportLineCommand $command
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(CreateImportLineCommand $command)
    {
        $line = new ImportLine(
            $command->getId(),
            $command->getImportId(),
            \json_encode($command->getCollection())
        );

        $this->repository->save($line);
    }
}
