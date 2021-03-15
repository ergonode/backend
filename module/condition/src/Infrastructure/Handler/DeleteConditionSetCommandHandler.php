<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Handler;

use Ergonode\Condition\Domain\Command\DeleteConditionSetCommand;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Webmozart\Assert\Assert;

class DeleteConditionSetCommandHandler
{
    private ConditionSetRepositoryInterface $repository;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        ConditionSetRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteConditionSetCommand $command): void
    {
        $conditionSet = $this->repository->load($command->getId());
        Assert::notNull($conditionSet);

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($conditionSet);
    }
}
