<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
interface Shopware6TaxRepositoryInterface
{
    /**
     * @param ExportProfileId $exportProfileId
     * @param float           $tax
     *
     * @return string|null
     */
    public function load(ExportProfileId $exportProfileId, float $tax): ?string;

    /**
     * @param ExportProfileId $exportProfileId
     * @param float           $tax
     * @param string          $shopwareId
     */
    public function save(ExportProfileId $exportProfileId, float $tax, string $shopwareId): void;

    /**
     * @param ExportProfileId $exportProfileId
     * @param float           $tax
     *
     * @return bool
     */
    public function exists(ExportProfileId $exportProfileId, float $tax): bool;
}
