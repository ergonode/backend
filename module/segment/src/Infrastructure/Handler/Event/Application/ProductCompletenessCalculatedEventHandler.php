<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Event\Application;

use Ergonode\Completeness\Application\Event\ProductCompletenessCalculatedEvent;
use Ergonode\Segment\Infrastructure\Service\SegmentProductService;

class ProductCompletenessCalculatedEventHandler
{
    private SegmentProductService $segmentService;

    public function __construct(SegmentProductService $segmentService)
    {
        $this->segmentService = $segmentService;
    }

    public function __invoke(ProductCompletenessCalculatedEvent $event): void
    {
        $this->segmentService->addProduct($event->getProductId());
    }
}
