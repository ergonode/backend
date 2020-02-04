<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Repository;

use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use Ramsey\Uuid\Uuid;

/**
 */
interface CategoryRepositoryInterface
{
    /**
     * @param Uuid $id
     *
     * @return ExportCategory
     */
    public function load(Uuid $id): ?ExportCategory;

    /**
     * @param ExportCategory $category
     */
    public function save(ExportCategory $category): void;
}
