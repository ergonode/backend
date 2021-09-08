<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;

class DeleteWorkflowCommandHandler
{
    private WorkflowRepositoryInterface $repository;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        WorkflowRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @throws ExistingRelationshipsException
     */
    public function __invoke(DeleteWorkflowCommand $command): void
    {
        $workflow = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $workflow,
            AbstractWorkflow::class,
            sprintf('Can\'t find workflow with ID "%s"', $command->getId())
        );

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($workflow);
    }
}
