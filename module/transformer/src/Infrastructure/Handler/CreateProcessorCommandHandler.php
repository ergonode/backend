<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Transformer\Domain\Command\CreateProcessorCommand;
use Ergonode\Transformer\Domain\Entity\Processor;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Infrastructure\Process\ProcessorProcess;

/**
 */
class CreateProcessorCommandHandler
{
    /**
     * @var TransformerRepositoryInterface
     */
    private $transformerRepository;

    /**
     * @var ProcessorProcess
     */
    private $processorProcess;

    /**
     * @param TransformerRepositoryInterface $transformerRepository
     * @param ProcessorProcess               $processorProcess
     */
    public function __construct(
        TransformerRepositoryInterface $transformerRepository,
        ProcessorProcess $processorProcess
    ) {
        $this->transformerRepository = $transformerRepository;
        $this->processorProcess = $processorProcess;
    }

    /**
     * @param CreateProcessorCommand $command
     */
    public function __invoke(CreateProcessorCommand $command)
    {
        $processor = new processor(
            $command->getId(),
            $command->getTransformerId(),
            $command->getImportId(),
            $command->getAction()
        );
        $this->transformerRepository->save($processor);
        $this->processorProcess->process($processor);
    }
}
