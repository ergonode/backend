<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\CreateTransformerCommand;
use Ergonode\Importer\Domain\Factory\TransformerFactory;
use Ergonode\Importer\Domain\Repository\TransformerRepositoryInterface;

class CreateTransformerCommandHandler
{
    private TransformerRepositoryInterface $repository;

    private TransformerFactory $factory;

    public function __construct(TransformerRepositoryInterface $repository, TransformerFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateTransformerCommand $command): void
    {
        $transformer = $this->factory->create($command->getId(), $command->getName(), $command->getKey());

        $this->repository->save($transformer);
    }
}
