<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Status;

use Ergonode\Workflow\Domain\Command\Status\CreateStatusCommand;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class CreateStatusCommandHandler
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
     * @param CreateStatusCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateStatusCommand $command)
    {
        $workflow = $this->repository->load($command->getId());
        Assert::notNull($workflow);

        if (!$workflow->hasStatus($command->getId())) {
            $workflow->addStatus($command->getId());
        }

        $this->repository->save($workflow);
    }
}
