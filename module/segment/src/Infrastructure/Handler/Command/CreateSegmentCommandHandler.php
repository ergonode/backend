<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\Segment\Domain\Command\CreateSegmentCommand;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;

class CreateSegmentCommandHandler
{
    private SegmentRepositoryInterface $repository;

    public function __construct(SegmentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateSegmentCommand $command): void
    {
        $segment = new Segment(
            $command->getId(),
            $command->getCode(),
            $command->getName(),
            $command->getDescription(),
            $command->getConditionSetId()
        );

        $this->repository->save($segment);
    }
}
