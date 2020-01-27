<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Transformer\Domain\Command\EndProcessImportLineCommand;
use Ergonode\Transformer\Domain\Repository\ProcessorRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class EndProcessImportLineCommandHandler
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
     * @param EndProcessImportLineCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(EndProcessImportLineCommand $command)
    {
        $transformer = $this->processorRepository->load($command->getId());

        Assert::notNull($transformer);

        $transformer->end();
        $this->processorRepository->save($transformer);
    }
}
