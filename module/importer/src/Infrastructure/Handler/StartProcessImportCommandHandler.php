<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\StartProcessImportCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;

/**
 * Class StartProcessImportCommandHandler
 */
class StartProcessImportCommandHandler
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
     * @param StartProcessImportCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(StartProcessImportCommand $command)
    {
        $import = $this->repository->load($command->getImportId());

        if (null === $import) {
            throw new \LogicException(\sprintf('Can\'t find import with id %s', $command->getImportId()->getValue()));
        }

        $import->process();
        $this->repository->save($import);
    }
}
