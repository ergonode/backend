<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\Segment\Domain\Command\UpdateSegmentCommand;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class UpdateSegmentCommandHandler
{
    /**
     * @var SegmentRepositoryInterface
     */
    private SegmentRepositoryInterface $repository;

    /**
     * @param SegmentRepositoryInterface $repository
     */
    public function __construct(SegmentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateSegmentCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateSegmentCommand $command)
    {
        $segment = $this->repository->load($command->getId());

        Assert::notNull($segment);

        $segment->changeName($command->getName());
        $segment->changeDescription($command->getDescription());
        $segment->changeConditionSet($command->getConditionSetId());

        $this->repository->save($segment);
    }
}
