<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Event;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\SharedKernel\Application\ApplicationEventInterface;

class CategoryDeletedEvent implements ApplicationEventInterface
{
    private AbstractCategory $category;

    public function __construct(AbstractCategory $category)
    {
        $this->category = $category;
    }

    public function getSegment(): AbstractCategory
    {
        return $this->category;
    }
}
