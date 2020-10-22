<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateProductCommand;
use Ergonode\Segment\Domain\Command\CalculateProductInSegmentCommand;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class CalculateProductCommandHandler
{
    /**
     * @var SegmentQueryInterface
     */
    private SegmentQueryInterface $query;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param SegmentQueryInterface $query
     * @param CommandBusInterface   $commandBus
     */
    public function __construct(
        SegmentQueryInterface $query,
        CommandBusInterface $commandBus
    ) {
        $this->query = $query;
        $this->commandBus = $commandBus;
    }

    /**
     * @param CalculateProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CalculateProductCommand $command): void
    {
        $productId = $command->getProductId();
        $segmentIds = $this->query->getAllSegmentIds();
        if (!empty($segmentIds)) {
            foreach ($segmentIds as $segmentId) {
                $segmentId = new SegmentId($segmentId);
                $this->commandBus->dispatch(new CalculateProductInSegmentCommand($segmentId, $productId));
            }
        }
    }
}
