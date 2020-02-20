<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\StartImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\Importer\Domain\Command\Import\UploadFileCommand;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Infrastructure\Provider\TransformerGeneratorProvider;
use Webmozart\Assert\Assert;

/**
 */
class UpdateFileCommandHandler
{
    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $sourceRepository;

    /**
     * @var TransformerRepositoryInterface
     */
    private TransformerRepositoryInterface $transformerRepository;

    /**
     * @var ImportRepositoryInterface
     */
    private ImportRepositoryInterface $importRepository;

    /**
     * @var TransformerGeneratorProvider
     */
    private TransformerGeneratorProvider $provider;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param SourceRepositoryInterface      $sourceRepository
     * @param TransformerRepositoryInterface $transformerRepository
     * @param ImportRepositoryInterface      $importRepository
     * @param TransformerGeneratorProvider   $provider
     * @param CommandBusInterface            $commandBus
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        ImportRepositoryInterface $importRepository,
        TransformerGeneratorProvider $provider,
        CommandBusInterface $commandBus
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
        $this->importRepository = $importRepository;
        $this->provider = $provider;
        $this->commandBus = $commandBus;
    }

    /**
     * @param UploadFileCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UploadFileCommand $command)
    {
        $source = $this->sourceRepository->load($command->getSourceId());
        Assert::notNull($source);
        $generator = $this->provider->provide($source->getType());
        $transformer = $generator->generate(TransformerId::generate(), 'name', $source);

        $import = new Import(
            $command->getId(),
            $command->getSourceId(),
            $transformer->getId(),
            $command->getFileName(),
        );

        $this->transformerRepository->save($transformer);
        $this->importRepository->save($import);

        $this->commandBus->dispatch(new StartImportCommand($import->getId()));
    }
}
