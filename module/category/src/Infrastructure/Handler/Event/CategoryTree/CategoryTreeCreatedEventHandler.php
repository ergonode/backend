<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Handler\Event\CategoryTree;

use Ergonode\Category\Domain\Event\Tree\CategoryTreeCreatedEvent;
use Ergonode\Category\Infrastructure\Persistence\Projector\Tree\DbalCategoryTreeCreatedEventProjector;

class CategoryTreeCreatedEventHandler
{
    private DbalCategoryTreeCreatedEventProjector $projector;

    public function __construct(DbalCategoryTreeCreatedEventProjector $projector)
    {
        $this->projector = $projector;
    }

    public function __invoke(CategoryTreeCreatedEvent $event): void
    {
        $this->projector->__invoke($event);
    }
}