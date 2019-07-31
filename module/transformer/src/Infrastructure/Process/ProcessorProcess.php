<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Process;

use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Transformer\Domain\Command\EndProcessImportLineCommand;
use Ergonode\Transformer\Domain\Command\ProcessImportLineCommand;
use Ergonode\Transformer\Domain\Command\StartProcessImportLineCommand;
use Ergonode\Transformer\Domain\Command\StopProcessImportLineCommand;
use Ergonode\Transformer\Domain\Entity\Processor;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class ProcessorProcess
{
    /**
     * @var ImportLineRepositoryInterface
     */
    private $lineRepository;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param ImportLineRepositoryInterface $lineRepository
     * @param MessageBusInterface           $messageBus
     */
    public function __construct(ImportLineRepositoryInterface $lineRepository, MessageBusInterface $messageBus)
    {
        $this->lineRepository = $lineRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @param Processor $processor
     */
    public function process(Processor $processor): void
    {
        try {
            $this->messageBus->dispatch(new StartProcessImportLineCommand($processor->getId()));
            $lines = $this->lineRepository->findCollectionByImport($processor->getImportId());
            foreach ($lines as $line) {
                $newCommand = new ProcessImportLineCommand($processor->getTransformerId(), json_decode($line->getContent(), true), $processor->getAction());
                $this->messageBus->dispatch($newCommand);
            }
            $this->messageBus->dispatch(new EndProcessImportLineCommand($processor->getId()));
        } catch (\Throwable $exception) {
            $this->messageBus->dispatch(new StopProcessImportLineCommand($processor->getId(), $exception->getMessage()));
        }
    }
}
