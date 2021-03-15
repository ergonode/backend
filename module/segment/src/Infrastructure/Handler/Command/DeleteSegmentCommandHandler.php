<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\Condition\Domain\Command\DeleteConditionSetCommand;
use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Segment\Domain\Command\DeleteSegmentCommand;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Webmozart\Assert\Assert;

class DeleteSegmentCommandHandler
{
    private SegmentRepositoryInterface $repository;

    private RelationshipsResolverInterface $relationshipsResolver;

    private CommandBusInterface $commandBus;

    public function __construct(
        SegmentRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver,
        CommandBusInterface $commandBus
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws ExistingRelationshipsException
     */
    public function __invoke(DeleteSegmentCommand $command): void
    {
        $segment = $this->repository->load($command->getId());
        Assert::isInstanceOf($segment, Segment::class, sprintf('Can\'t find segment with ID "%s"', $command->getId()));

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }
        if ($segment->getConditionSetId() &&
            null === $this->relationshipsResolver->resolve($segment->getConditionSetId())
        ) {
            $this->commandBus->dispatch(
                new DeleteConditionSetCommand(
                    $segment->getConditionSetId(),
                ),
            );
        }

        $this->repository->delete($segment);
    }
}
