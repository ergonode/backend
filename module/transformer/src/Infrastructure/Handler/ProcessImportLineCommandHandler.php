<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Transformer\Domain\Command\ProcessImportLineCommand;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Infrastructure\Process\TransformProcess;
use Ergonode\Transformer\Infrastructure\Provider\ImportActionProvider;

/**
 */
class ProcessImportLineCommandHandler
{
    /**
     * @var TransformerRepositoryInterface
     */
    private $transformerRepository;

    /**
     * @var TransformProcess
     */
    private $transformationProcess;

    /**
     * @var ImportActionProvider
     */
    private $importActionProvider;

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
     * @param ProcessImportLineCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(ProcessImportLineCommand $command)
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
