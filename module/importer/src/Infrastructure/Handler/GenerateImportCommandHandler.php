<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\GenerateImportCommand;
use Ergonode\Importer\Domain\Command\Import\StartImportCommand;
use Ergonode\Importer\Domain\Entity\AbstractImport;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\ImporterMagento2\Infrastructure\Generator\Magento2TransformerGenerator;
use Ergonode\Transformer\Domain\Entity\Processor;
use Ergonode\Transformer\Domain\Entity\ProcessorId;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Ergonode\Transformer\Infrastructure\Action\ProductSimpleImportAction;
use Webmozart\Assert\Assert;

/**
 */
class GenerateImportCommandHandler
{
    /**
     * @var ImportRepositoryInterface
     */
    private $importRepository;

    /**
     * @var Magento2TransformerGenerator
     */
    private Magento2TransformerGenerator $generator;

    /**
     * @var CommandBusInterface
     */
    private $commandBus;

    /**
     * @param ImportRepositoryInterface $importRepository
     * @param Magento2TransformerGenerator $generator
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        ImportRepositoryInterface $importRepository,
        Magento2TransformerGenerator $generator,
        CommandBusInterface $commandBus
    ) {
        $this->importRepository = $importRepository;
        $this->generator = $generator;
        $this->commandBus = $commandBus;
    }

    /**
     * @param GenerateImportCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(GenerateImportCommand $command)
    {
        $import = $this->importRepository->load($command->getId());

        Assert::isInstanceOf($import, AbstractImport::class);

        $transformerId = TransformerId::generate();
        $transformer = $this->generator->generate($transformerId, $import->getName(), $command->getConfiguration());

        $process = new Processor(ProcessorId::generate(), $transformer->getId(), $command->getId(), ProductSimpleImportAction::TYPE);

        $command = new StartImportCommand($process->getId());

        $this->commandBus->dispatch($command);
    }
}
