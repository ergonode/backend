<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector\Product;

use Ergonode\Product\Domain\Event\ProductValueRemovedEvent;
use Ergonode\Completeness\Infrastructure\Persistence\Projector\AbstractProductCompletenessProjector;

class ProductValueRemovedEventProjector extends AbstractProductCompletenessProjector
{
    public function __invoke(ProductValueRemovedEvent $event): void
    {
        $this->update($event->getAggregateId());
    }
}
