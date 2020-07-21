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
interface Shopware6LanguageRepositoryInterface
{
    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $name
     *
     * @return string|null
     */
    public function load(ExportProfileId $exportProfileId, string $name): ?string;

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $name
     * @param string          $shopwareId
     */
    public function save(ExportProfileId $exportProfileId, string $name, string $shopwareId): void;

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $name
     *
     * @return bool
     */
    public function exists(ExportProfileId $exportProfileId, string $name): bool;
}
