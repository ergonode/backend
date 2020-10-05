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
    private WorkflowRepositoryInterface $repository;

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
        $roleIds = $command->getRoleIds();
        $conditionSetId = $command->getConditionSetId();
        $workflow = $this->repository->load($command->getWorkflowId());
        Assert::notNull($workflow);

        $source = $command->getSource();
        $destination = $command->getDestination();

        if (!$workflow->hasStatus($source)) {
            $workflow->addStatus($source);
        }

        if (!$workflow->hasStatus($destination)) {
            $workflow->addStatus($destination);
        }

        $workflow->addTransition($command->getSource(), $command->getDestination());
        if ($conditionSetId) {
            $workflow->getTransition(
                $command->getSource(),
                $command->getDestination()
            )->changeConditionSetId($conditionSetId);
        }

        if (!empty($roleIds)) {
            $workflow->getTransition(
                $command->getSource(),
                $command->getDestination()
            )->changeRoleIds($roleIds);
        }

        $this->repository->save($workflow);
    }
}
