<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
interface Shopware6PropertyGroupRepositoryInterface
{
    /**
     * @param ExportProfileId $exportProfileId
     * @param AttributeId     $attributeId
     *
     * @return string|null
     */
    public function load(ExportProfileId $exportProfileId, AttributeId $attributeId): ?string;

    /**
     * @param ExportProfileId $exportProfileId
     * @param AttributeId     $attributeId
     * @param string          $shopwareId
     */
    public function save(ExportProfileId $exportProfileId, AttributeId $attributeId, string $shopwareId): void;

    /**
     * @param ExportProfileId $exportProfileId
     * @param AttributeId     $attributeId
     *
     * @return bool
     */
    public function exists(ExportProfileId $exportProfileId, AttributeId $attributeId): bool;
}
