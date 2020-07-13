<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
interface Shopware6CustomFieldQueryInterface
{
    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $shopwareId
     *
     * @return AttributeId|null
     */
    public function loadByShopwareId(ExportProfileId $exportProfileId, string $shopwareId): ?AttributeId;

    /**
     * @param ExportProfileId    $exportProfileId
     * @param \DateTimeImmutable $dateTime
     */
    public function clearBefore(ExportProfileId $exportProfileId, \DateTimeImmutable $dateTime): void;
}
