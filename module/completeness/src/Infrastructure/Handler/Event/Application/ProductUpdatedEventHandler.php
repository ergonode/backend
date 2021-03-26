<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Handler\Event\Application;

use Ergonode\Product\Application\Event\ProductUpdatedEvent;
use Ergonode\Completeness\Infrastructure\Persistence\Manager\CompletenessManager;

class ProductUpdatedEventHandler
{
    private CompletenessManager $manager;

    public function __construct(CompletenessManager $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(ProductUpdatedEvent $event): void
    {
        $this->manager->recalculateProduct($event->getProduct()->getId());
    }
}
