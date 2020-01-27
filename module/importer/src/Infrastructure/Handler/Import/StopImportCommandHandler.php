<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\StopImportCommand;
use Ergonode\Transformer\Domain\Entity\Processor;
use Ergonode\Transformer\Domain\Repository\ProcessorRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class StopImportCommandHandler
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
     * @param StopImportCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(StopImportCommand $command)
    {
        $processor = $this->repository->load($command->getId());

        Assert::isInstanceOf($processor, Processor::class);

        $processor->stop($command->getReason());
        $this->repository->save($processor);
    }
}
