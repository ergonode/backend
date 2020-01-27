<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Transformer\Domain\Command\StopProcessImportLineCommand;
use Ergonode\Transformer\Domain\Entity\Processor;
use Ergonode\Transformer\Domain\Repository\ProcessorRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class StopProcessImportLineCommandHandler
{
    /**
     * @var ProcessorRepositoryInterface
     */
    private ProcessorRepositoryInterface $processorRepository;

    /**
     * @param ProcessorRepositoryInterface $processorRepository
     */
    public function __construct(ProcessorRepositoryInterface $processorRepository)
    {
        $this->processorRepository = $processorRepository;
    }

    /**
     * @param StopProcessImportLineCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(StopProcessImportLineCommand $command)
    {
        $processor = $this->processorRepository->load($command->getId());

        Assert::isInstanceOf($processor, Processor::class);

        $processor->stop($command->getReason());
        $this->processorRepository->save($processor);
    }
}
