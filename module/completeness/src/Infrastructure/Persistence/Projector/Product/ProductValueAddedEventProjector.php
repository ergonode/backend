<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector\Product;

use Ergonode\Product\Domain\Event\ProductValueAddedEvent;
use Ergonode\Completeness\Infrastructure\Persistence\Projector\AbstractProductCompletenessProjector;

class ProductValueAddedEventProjector extends AbstractProductCompletenessProjector
{
    public function __invoke(ProductValueAddedEvent $event): void
    {
        $this->update($event->getAggregateId());
    }
}
