<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Handler\Event\CategoryTree;

use Ergonode\Category\Infrastructure\Persistence\Projector\Tree\DbalCategoryTreeCategoriesChangedEventProjector;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoriesChangedEvent;

class CategoryTreeCategoriesChangedEventHandler
{
    private DbalCategoryTreeCategoriesChangedEventProjector $projector;

    public function __construct(DbalCategoryTreeCategoriesChangedEventProjector $projector)
    {
        $this->projector = $projector;
    }

    public function __invoke(CategoryTreeCategoriesChangedEvent $event): void
    {
        $this->projector->__invoke($event);
    }
}