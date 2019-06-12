<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Transformer\Domain\Command\StopProcessImportLineCommand;
use Ergonode\Transformer\Domain\Repository\ProcessorRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class StopProcessImportLineCommandHandler
{
    /**
     * @var ProcessorRepositoryInterface
     */
    private $processorRepository;

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
        $transformer = $this->processorRepository->load($command->getId());

        Assert::notNull($transformer);

        $transformer->stop($command->getReason());
        $this->processorRepository->save($transformer);
    }
}
