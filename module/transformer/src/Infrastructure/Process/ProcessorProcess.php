<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Process;

use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Transformer\Domain\Command\EndImportCommand;
use Ergonode\Transformer\Domain\Command\ProcessImportCommand;
use Ergonode\Transformer\Domain\Command\StartImportCommand;
use Ergonode\Transformer\Domain\Command\StopImportCommand;
use Ergonode\Transformer\Domain\Entity\Processor;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class ProcessorProcess
{
    /**
     * @var ImportLineRepositoryInterface
     */
    private ImportLineRepositoryInterface $lineRepository;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

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
            $this->messageBus->dispatch(new StartImportCommand($processor->getId()));
            $lines = $this->lineRepository->findCollectionByImport($processor->getImportId());
            foreach ($lines as $line) {
                $newCommand = new ProcessImportCommand(
                    $processor->getTransformerId(),
                    json_decode($line->getContent(), true),
                    $processor->getAction()
                );
                $this->messageBus->dispatch($newCommand);
            }
            $this->messageBus->dispatch(new EndImportCommand($processor->getId()));
        } catch (\Throwable $exception) {
            $this
                ->messageBus
                ->dispatch(new StopImportCommand($processor->getId(), $exception->getMessage()));
        }
    }
}
