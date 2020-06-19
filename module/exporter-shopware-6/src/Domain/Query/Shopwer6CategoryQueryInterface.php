<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\ExporterShopware6\Domain\Entity\Catalog\Shopware6Category;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
interface Shopwer6CategoryQueryInterface
{
    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $shopwareId
     *
     * @return Shopware6Category|null
     */
    public function loadByShopwareId(ExportProfileId $exportProfileId, string $shopwareId): ?Shopware6Category;

    /**
     * @param ExportProfileId    $exportProfileId
     * @param \DateTimeImmutable $dateTime
     */
    public function clearBefore(ExportProfileId $exportProfileId, \DateTimeImmutable $dateTime): void;
}
