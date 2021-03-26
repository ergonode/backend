<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\Segment\Domain\Command\UpdateSegmentCommand;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Segment\Application\Event\SegmentUpdatedEvent;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class UpdateSegmentCommandHandler
{
    private SegmentRepositoryInterface $repository;

    private ApplicationEventBusInterface $eventBus;

    public function __construct(SegmentRepositoryInterface $repository, ApplicationEventBusInterface $eventBus)
    {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateSegmentCommand $command): void
    {
        $segment = $this->repository->load($command->getId());

        Assert::notNull($segment);

        $segment->changeName($command->getName());
        $segment->changeDescription($command->getDescription());
        $segment->changeConditionSet($command->getConditionSetId());

        $this->repository->save($segment);
        $this->eventBus->dispatch(new SegmentUpdatedEvent($segment));
    }
}
