<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector\Product;

use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Completeness\Infrastructure\Persistence\Projector\AbstractProductCompletenessProjector;

class ProductValueChangedEventProjector extends AbstractProductCompletenessProjector
{
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $this->update($event->getAggregateId());
    }
}
