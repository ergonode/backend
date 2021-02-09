<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Handler\Event\CategoryTree;

use Ergonode\Category\Infrastructure\Persistence\Projector\Tree\DbalCategoryTreeCreatedEventProjector;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoryAddedEvent;

class CategoryTreeCategoryAddedEventHandler
{
    private DbalCategoryTreeCreatedEventProjector $projector;

    public function __construct(DbalCategoryTreeCreatedEventProjector $projector)
    {
        $this->projector = $projector;
    }

    public function __invoke(CategoryTreeCategoryAddedEvent $event): void
    {
        $this->projector->__invoke($event);
    }
}