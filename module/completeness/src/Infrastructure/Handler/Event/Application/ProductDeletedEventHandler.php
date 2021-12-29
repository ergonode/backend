<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Handler\Event\Application;

use Ergonode\Completeness\Infrastructure\Persistence\Manager\CompletenessManager;
use Ergonode\Product\Application\Event\ProductDeletedEvent;

class ProductDeletedEventHandler
{
    private CompletenessManager $manager;

    public function __construct(CompletenessManager $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(ProductDeletedEvent $event): void
    {
        $this->manager->removeProduct($event->getProduct()->getId());
    }
}
