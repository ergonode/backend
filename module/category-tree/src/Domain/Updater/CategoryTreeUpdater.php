<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Updater;

use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class CategoryTreeUpdater
{
    /**
     * @param CategoryTree       $categoryTree
     * @param TranslatableString $name
     *
     * @return CategoryTree
     */
    public function update(CategoryTree $categoryTree, TranslatableString $name)
    {
        $categoryTree->changeTitle($name);

        return $categoryTree;
    }
}
