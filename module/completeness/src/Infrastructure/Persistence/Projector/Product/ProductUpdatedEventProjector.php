<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector\Product;

use Ergonode\Completeness\Infrastructure\Persistence\Projector\AbstractProductCompletenessProjector;
use Ergonode\Product\Application\Event\ProductUpdatedEvent;

class ProductUpdatedEventProjector extends AbstractProductCompletenessProjector
{
    public function __invoke(ProductUpdatedEvent $event): void
    {
        $this->update($event->getProduct()->getId());
    }
}
