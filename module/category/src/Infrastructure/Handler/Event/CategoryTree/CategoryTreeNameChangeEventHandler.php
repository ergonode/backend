<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Handler\Event\CategoryTree;

use Ergonode\Category\Domain\Event\Tree\CategoryTreeNameChangedEvent;
use Ergonode\Category\Infrastructure\Persistence\Projector\Tree\DbalCategoryTreeNameChangedEventProjector;

class CategoryTreeNameChangeEventHandler
{
    private DbalCategoryTreeNameChangedEventProjector $projector;

    public function __construct(DbalCategoryTreeNameChangedEventProjector $projector)
    {
        $this->projector = $projector;
    }

    public function __invoke(CategoryTreeNameChangedEvent  $event): void
    {
        $this->projector->__invoke($event);
    }
}