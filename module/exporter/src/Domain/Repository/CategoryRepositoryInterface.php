<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Repository;

use Ergonode\Exporter\Domain\Entity\ExportCategory;

/**
 */
interface CategoryRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return ExportCategory
     */
    public function load(string $id): ?ExportCategory;

    /**
     * @param ExportCategory $category
     */
    public function save(ExportCategory $category): void;
}
