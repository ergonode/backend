<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\StopProcessImportCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;

/**
 */
class StopProcessImportCommandHandler
{
    /**
     * @var ImportRepositoryInterface;
     */
    private $repository;

    /**
     * @param ImportRepositoryInterface $repository
     */
    public function __construct(ImportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param StopProcessImportCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(StopProcessImportCommand $command)
    {
        $import = $this->repository->load($command->getImportId());

        if (null === $import) {
            throw new \LogicException(\sprintf('Can\'t find import with id %s', $command->getImportId()->getValue()));
        }

        $import->stop($command->getReason());
        $this->repository->save($import);
    }
}
