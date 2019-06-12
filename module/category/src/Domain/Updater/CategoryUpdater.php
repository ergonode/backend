<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Updater;

use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class CategoryUpdater
{
    /**
     * @param Category           $category
     * @param TranslatableString $name
     *
     * @return Category
     */
    public function update(Category $category, TranslatableString $name): Category
    {
        $category->changeTitle($name);

        return $category;
    }
}
