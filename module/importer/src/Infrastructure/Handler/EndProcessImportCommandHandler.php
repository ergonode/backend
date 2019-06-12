<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\EndProcessImportCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Transformer\Domain\Command\CreateProcessorCommand;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class EndProcessImportCommandHandler
{
    /**
     * @var ImportRepositoryInterface;
     */
    private $repository;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param ImportRepositoryInterface $repository
     * @param MessageBusInterface       $messageBus
     */
    public function __construct(
        ImportRepositoryInterface $repository,
        MessageBusInterface $messageBus
    ) {
        $this->repository = $repository;
        $this->messageBus = $messageBus;
    }

    /**
     * @param EndProcessImportCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(EndProcessImportCommand $command)
    {
        $import = $this->repository->load($command->getImportId());

        if (null === $import) {
            throw new \LogicException(\sprintf('Can\'t find import witch id %s', $command->getImportId()->getValue()));
        }

        $import->end();
        $this->repository->save($import);
        if ($command->getTransformerId() && $command->getAction()) {
            $command = new CreateProcessorCommand($command->getImportId(), $command->getTransformerId(), $command->getAction());
            $this->messageBus->dispatch($command);
        }
    }
}
