<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Repository;

use Ergonode\Category\Domain\Entity\Category;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

/**
 */
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
     * @return Category|Category
     */
    public function load(CategoryId $id): ?Category;

    /**
     * @param Category $category
     */
    public function save(Category $category): void;

    /**
     * @param Category $category
     */
    public function delete(Category $category): void;
}
