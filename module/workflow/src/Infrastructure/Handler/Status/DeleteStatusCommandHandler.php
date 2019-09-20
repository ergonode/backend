<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Status;

use Ergonode\Core\Application\Exception\NotImplementedException;
use Ergonode\Workflow\Domain\Command\Status\DeleteStatusCommand;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @todo Implement workflow status delete
 */
class DeleteStatusCommandHandler
{
    /**
     * @var StatusRepositoryInterface
     */
    private $repository;

    /**
     * @param StatusRepositoryInterface $repository
     */
    public function __construct(StatusRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteStatusCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(DeleteStatusCommand $command)
    {
        $status = $this->repository->load($command->getId());
        Assert::notNull($status);
        $status->remove();
        $this->repository->save($status);
    }
}
