<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\ExporterShopware6\Domain\Entity\Catalog\Shopware6Category;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
interface Shopware6CategoryRepositoryInterface
{
    /**
     * @param ExportProfileId $exportProfileId
     * @param CategoryId      $categoryId
     *
     * @return Shopware6Category|null
     */
    public function load(ExportProfileId $exportProfileId, CategoryId $categoryId): ?Shopware6Category;

    /**
     * @param ExportProfileId $exportProfileId
     * @param CategoryId      $categoryId
     * @param string          $shopwareId
     */
    public function save(ExportProfileId $exportProfileId, CategoryId $categoryId, string $shopwareId): void;

    /**
     * @param ExportProfileId $exportProfileId
     * @param CategoryId      $categoryId
     *
     * @return bool
     */
    public function exists(ExportProfileId $exportProfileId, CategoryId $categoryId): bool;
}
