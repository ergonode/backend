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
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento2\Infrastructure\Generator\Magento2TransformerGenerator;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class GenerateImportCommandHandler
{
    /**
     * @var ImportRepositoryInterface
     */
    private ImportRepositoryInterface $importRepository;

    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $sourceRepository;

    /**
     * @var TransformerRepositoryInterface
     */
    private TransformerRepositoryInterface $transformerRepository;

    /**
     * @var Magento2TransformerGenerator
     */
    private Magento2TransformerGenerator $generator;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ImportRepositoryInterface      $importRepository
     * @param SourceRepositoryInterface      $sourceRepository
     * @param TransformerRepositoryInterface $transformerRepository
     * @param Magento2TransformerGenerator   $generator
     * @param CommandBusInterface            $commandBus
     */
    public function __construct(
        ImportRepositoryInterface $importRepository,
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        Magento2TransformerGenerator $generator,
        CommandBusInterface $commandBus
    ) {
        $this->importRepository = $importRepository;
        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
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
        $source = $this->sourceRepository->load($command->getId());

        Assert::isInstanceOf($source, AbstractSource::class);

        $transformerId = TransformerId::generate();
        $transformer = $this->generator->generate($transformerId, 'Name', $command->getConfiguration());
        $this->transformerRepository->save($transformer);

        $import = new Import(ImportId::generate(), $command->getId(), $transformerId);
        $this->importRepository->save($import);

        $this->commandBus->dispatch(new StartImportCommand($import->getId()));
    }
}
