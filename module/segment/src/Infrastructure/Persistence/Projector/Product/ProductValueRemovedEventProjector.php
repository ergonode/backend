<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Projector\Product;

use Ergonode\Product\Domain\Event\ProductValueRemovedEvent;
use Ergonode\Segment\Infrastructure\Persistence\Projector\AbstractDbalProductUpdateEventProjector;

class ProductValueRemovedEventProjector extends AbstractDbalProductUpdateEventProjector
{
    public function __invoke(ProductValueRemovedEvent $event): void
    {
        $this->update($event->getAggregateId());
    }
}
