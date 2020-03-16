<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Status;

use Ergonode\Workflow\Domain\Command\Status\CreateStatusCommand;
use Ergonode\Workflow\Domain\Factory\StatusFactory;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;

/**
 */
class CreateStatusCommandHandler
{
    /**
     * @var StatusRepositoryInterface
     */
    private StatusRepositoryInterface $repository;

    /**
     * @var StatusFactory
     */
    private StatusFactory $factory;

    /**
     * @param StatusRepositoryInterface $repository
     * @param StatusFactory             $factory
     */
    public function __construct(StatusRepositoryInterface $repository, StatusFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param CreateStatusCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateStatusCommand $command)
    {
        $status = $this->factory->create(
            $command->getCode(),
            $command->getColor(),
            $command->getName(),
            $command->getDescription()
        );

        $this->repository->save($status);
    }
}
