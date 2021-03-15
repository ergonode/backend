<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector\Product;

use Ergonode\Product\Domain\Event\ProductTemplateChangedEvent;
use Ergonode\Completeness\Infrastructure\Persistence\Projector\AbstractTemplateCompletenessProjector;

class ProductTemplateChangedEventProjector extends AbstractTemplateCompletenessProjector
{
    public function __invoke(ProductTemplateChangedEvent $event): void
    {
        $this->update($event->getTemplateId());
    }
}
