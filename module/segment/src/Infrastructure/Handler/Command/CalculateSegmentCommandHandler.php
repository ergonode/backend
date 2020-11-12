<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Segment\Domain\Command\CalculateProductInSegmentCommand;
use Ergonode\Segment\Domain\Command\CalculateSegmentCommand;
use Ergonode\Segment\Infrastructure\Service\SegmentProductService;

class CalculateSegmentCommandHandler
{
    private ProductQueryInterface $query;

    private CommandBusInterface $commandBus;

    private SegmentProductService $service;

    public function __construct(
        ProductQueryInterface $query,
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
    public function __invoke(CalculateSegmentCommand $command): void
    {
        $segmentId = $command->getSegmentId();
        $productIds = $this->query->getAllIds();
        foreach ($productIds as $productId) {
            $productId = new ProductId($productId);
            $this->service->add($segmentId, $productId);
            $this->commandBus->dispatch(new CalculateProductInSegmentCommand($segmentId, $productId));
        }
    }
}
