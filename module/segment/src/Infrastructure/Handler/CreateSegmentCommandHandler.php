<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler;

use Ergonode\Segment\Domain\Command\CreateSegmentCommand;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;

/**
 */
class CreateSegmentCommandHandler
{
    /**
     * @var SegmentRepositoryInterface
     */
    private $repository;

    /**
     * @param SegmentRepositoryInterface $repository
     */
    public function __construct(SegmentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateSegmentCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateSegmentCommand $command)
    {
        $segment = new Segment(
            $command->getId(),
            $command->getCode(),
            $command->getName(),
            $command->getDescription()
        );

        $this->repository->save($segment);
    }
}
