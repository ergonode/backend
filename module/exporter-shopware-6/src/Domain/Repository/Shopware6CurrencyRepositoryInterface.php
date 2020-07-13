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
interface Shopware6CurrencyRepositoryInterface
{
    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $iso
     *
     * @return string|null
     */
    public function load(ExportProfileId $exportProfileId, string $iso): ?string;

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $iso
     * @param string          $shopwareId
     */
    public function save(ExportProfileId $exportProfileId, string $iso, string $shopwareId): void;

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $iso
     *
     * @return bool
     */
    public function exists(ExportProfileId $exportProfileId, string $iso): bool;
}
