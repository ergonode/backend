<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Event\Application;

use Ergonode\Product\Application\Event\ProductDeletedEvent;
use Ergonode\Segment\Infrastructure\Service\SegmentProductService;

class ProductDeletedEventHandler
{
    private SegmentProductService $segmentService;

    public function __construct(SegmentProductService $segmentService)
    {
        $this->segmentService = $segmentService;
    }

    public function __invoke(ProductDeletedEvent $event): void
    {
        $this->segmentService->removeProduct($event->getProduct()->getId());
    }
}
