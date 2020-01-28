<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Repository;

use Ergonode\Exporter\Domain\Entity\Category;

/**
 */
interface CategoryRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return Category
     */
    public function load(string $id): ?Category;

    /**
     * @param Category $category
     */
    public function save(Category $category): void;
}
