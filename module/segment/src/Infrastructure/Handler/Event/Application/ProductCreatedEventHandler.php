<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Event\Application;

use Ergonode\Product\Application\Event\ProductCreatedEvent;
use Ergonode\Segment\Infrastructure\Service\SegmentProductService;

class ProductCreatedEventHandler
{
    private SegmentProductService $segmentService;

    public function __construct(SegmentProductService $segmentService)
    {
        $this->segmentService = $segmentService;
    }

    public function __invoke(ProductCreatedEvent $event): void
    {
        $this->segmentService->addProduct($event->getProduct()->getId());
    }
}
