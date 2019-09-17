<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler;

use Ergonode\Segment\Domain\Command\DeleteSegmentCommand;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteSegmentCommandHandler
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
     * @param DeleteSegmentCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteSegmentCommand $command)
    {
        $conditionSet = $this->repository->load($command->getId());
        Assert::notNull($conditionSet);

        $this->repository->delete($conditionSet);
    }
}
