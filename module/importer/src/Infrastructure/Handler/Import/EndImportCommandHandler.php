<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\EndImportCommand;
use Ergonode\Transformer\Domain\Repository\ProcessorRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class EndImportCommandHandler
{
    /**
     * @var ProcessorRepositoryInterface
     */
    private ProcessorRepositoryInterface $repository;

    /**
     * @param ProcessorRepositoryInterface $repository
     */
    public function __construct(ProcessorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param EndImportCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(EndImportCommand $command)
    {
        $process = $this->repository->load($command->getId());

        Assert::notNull($process);

        $process->end();
        $this->repository->save($process);
    }
}
