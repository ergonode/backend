<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Infrastructure\Process\TransformProcess;
use Ergonode\Transformer\Infrastructure\Provider\ImportActionProvider;

/**
 */
class ProcessImportCommandHandler
{
    /**
     * @var TransformerRepositoryInterface
     */
    private TransformerRepositoryInterface $transformerRepository;

    /**
     * @var TransformProcess
     */
    private TransformProcess $transformationProcess;

    /**
     * @var ImportActionProvider
     */
    private ImportActionProvider $importActionProvider;

    /**
     * @param TransformerRepositoryInterface $transformerRepository
     * @param TransformProcess               $transformationProcess
     * @param ImportActionProvider           $importActionProvider
     */
    public function __construct(
        TransformerRepositoryInterface $transformerRepository,
        TransformProcess $transformationProcess,
        ImportActionProvider $importActionProvider
    ) {
        $this->transformerRepository = $transformerRepository;
        $this->transformationProcess = $transformationProcess;
        $this->importActionProvider = $importActionProvider;
    }

    /**
     * @param ProcessImportCommand $command
     */
    public function __invoke(ProcessImportCommand $command)
    {
        $transformer = $this->transformerRepository->load($command->getTransformerId());
        $content = $command->getRow();
        $action = $this->importActionProvider->provide($command->getAction());

        if (!$transformer) {
            throw new \RuntimeException(sprintf('Can\'t find transformer %s', $command->getTransformerId()));
        }

        if (!$action) {
            throw new \RuntimeException(sprintf('Can\'t find action %s', $command->getAction()));
        }

        if ($content) {
            $this->transformationProcess->process($transformer, $action, $content);
        }
    }
}

//$line = new ImportLine(
//    $command->getId(),
//    $command->getImportId(),
//    \json_encode($command->getCollection())
//);
//
//$this->repository->save($line);
