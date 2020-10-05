<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Status;

use Ergonode\Workflow\Domain\Command\Status\UpdateStatusCommand;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Domain\Entity\Status;

/**
 */
class UpdateStatusCommandHandler
{
    /**
     * @var StatusRepositoryInterface
     */
    private StatusRepositoryInterface $repository;

    /**
     * @param StatusRepositoryInterface $repository
     */
    public function __construct(StatusRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateStatusCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateStatusCommand $command)
    {
        $status = $this->repository->load($command->getId());

        Assert::isInstanceOf($status, Status::class);

        $status->changeName($command->getName());
        $status->changeDescription($command->getDescription());
        $status->changeColor($command->getColor());

        $this->repository->save($status);
    }
}
