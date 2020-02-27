<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\StartImportCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Importer\Infrastructure\Provider\ImportProcessorProvider;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;

/**
 */
class StartImportCommandHandler
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
     * @var ImportProcessorProvider
     */
    private ImportProcessorProvider $provider;

    /**
     * @param ImportRepositoryInterface $importRepository
     * @param SourceRepositoryInterface $sourceRepository
     * @param ImportProcessorProvider   $provider
     */
    public function __construct(
        ImportRepositoryInterface $importRepository,
        SourceRepositoryInterface $sourceRepository,
        ImportProcessorProvider $provider
    ) {
        $this->importRepository = $importRepository;
        $this->sourceRepository = $sourceRepository;
        $this->provider = $provider;
    }

    /**
     * @param StartImportCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(StartImportCommand $command)
    {
        $import = $this->importRepository->load($command->getId());
        Assert::notNull($import);
        $source = $this->sourceRepository->load($import->getSourceId());
        Assert::notNull($source);


        $import->start();
        $this->importRepository->save($import);

        $processor = $this->provider->provide($source->getType());
        $processor->start($import);
    }
}
