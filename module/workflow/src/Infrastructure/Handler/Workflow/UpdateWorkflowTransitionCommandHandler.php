<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class UpdateWorkflowTransitionCommandHandler
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
     * @param UpdateWorkflowTransitionCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateWorkflowTransitionCommand $command)
    {
        $workflow = $this->repository->load($command->getWorkflowId());
        Assert::notNull($workflow);

        $source = $command->getTransition()->getSource();
        $destination = $command->getTransition()->getDestination();

        if (!$workflow->hasStatus($source)) {
            $workflow->addStatus($source);
        }

        if (!$workflow->hasStatus($destination)) {
            $workflow->addStatus($destination);
        }

        $workflow->changeTransition($source, $destination, $command->getTransition());

        $this->repository->save($workflow);
    }
}
