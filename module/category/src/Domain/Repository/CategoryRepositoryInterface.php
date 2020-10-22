<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Repository;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

interface CategoryRepositoryInterface
{
    /**
     * @param CategoryId $id
     *
     * @return bool
     */
    public function exists(CategoryId $id): bool;

    /**
     * @param CategoryId $id
     *
     * @return AbstractCategory
     */
    public function load(CategoryId $id): ?AbstractCategory;

    /**
     * @param AbstractCategory $category
     */
    public function save(AbstractCategory $category): void;

    /**
     * @param AbstractCategory $category
     */
    public function delete(AbstractCategory $category): void;
}
