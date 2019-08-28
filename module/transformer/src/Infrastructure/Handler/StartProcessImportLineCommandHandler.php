<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Transformer\Domain\Command\StartProcessImportLineCommand;
use Ergonode\Transformer\Domain\Repository\ProcessorRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class StartProcessImportLineCommandHandler
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
     * @param StartProcessImportLineCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(StartProcessImportLineCommand $command)
    {
        $transformer = $this->processorRepository->load($command->getId());

        Assert::notNull($transformer);

        $transformer->process();
        $this->processorRepository->save($transformer);
    }
}
