<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Handler\Event\CategoryTree;

use Ergonode\Category\Domain\Event\Tree\CategoryTreeDeletedEvent;
use Ergonode\Category\Infrastructure\Persistence\Projector\Tree\DbalCategoryTreeDeletedEventProjector;

class CategoryTreeDeletedEventHandler
{
    private DbalCategoryTreeDeletedEventProjector $projector;

    public function __construct(DbalCategoryTreeDeletedEventProjector $projector)
    {
        $this->projector = $projector;
    }

    public function __invoke(CategoryTreeDeletedEvent  $event): void
    {
        $this->projector->__invoke($event);
    }
}