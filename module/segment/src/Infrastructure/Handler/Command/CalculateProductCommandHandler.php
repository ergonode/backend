<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateProductCommand;
use Ergonode\Segment\Domain\Command\CalculateProductInSegmentCommand;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Infrastructure\Service\SegmentProductService;

class CalculateProductCommandHandler
{
    private SegmentQueryInterface $query;

    private CommandBusInterface $commandBus;

    private SegmentProductService $service;

    public function __construct(
        SegmentQueryInterface $query,
        CommandBusInterface $commandBus,
        SegmentProductService $service
    ) {
        $this->query = $query;
        $this->commandBus = $commandBus;
        $this->service = $service;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CalculateProductCommand $command): void
    {
        $productId = $command->getProductId();
        $segmentIds = $this->query->getAllSegmentIds();

        $this->service->addByProduct($productId);
        foreach ($segmentIds as $segmentId) {
            $segmentId = new SegmentId($segmentId);
            $this->commandBus->dispatch(new CalculateProductInSegmentCommand($segmentId, $productId), true);
        }
    }
}
