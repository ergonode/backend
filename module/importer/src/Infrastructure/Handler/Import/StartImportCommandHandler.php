<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\StartImportCommand;
use Ergonode\Transformer\Domain\Entity\Processor;
use Ergonode\Transformer\Domain\Repository\ProcessorRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class StartImportCommandHandler
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
     * @param StartImportCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(StartImportCommand $command)
    {
        $process = $this->repository->load($command->getId());

        Assert::notNull($process); Assert::isInstanceOf($process, Processor::class);

        $process->process();
        $this->repository->save($process);
    }
}
