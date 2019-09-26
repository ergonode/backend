<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\AddWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class AddWorkflowTransitionCommandHandler
{
    /**
     * @var WorkflowRepositoryInterface
     */
    private $repository;

    /**
     * @param WorkflowRepositoryInterface $repository
     */
    public function __construct(WorkflowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AddWorkflowTransitionCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(AddWorkflowTransitionCommand $command)
    {
        $workflow = $this->repository->load($command->getWorkflowId());
        Assert::notNull($workflow);

        $workflow->addTransition($command->getTransition());

        $this->repository->save($workflow);
    }
}
