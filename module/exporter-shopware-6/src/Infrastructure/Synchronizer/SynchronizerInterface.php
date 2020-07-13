<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
interface SynchronizerInterface
{
    /**
     * @param ExportId                  $id
     * @param Shopware6ExportApiProfile $profile
     */
    public function synchronize(ExportId $id, Shopware6ExportApiProfile $profile): void;
}
