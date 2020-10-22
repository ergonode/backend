<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

interface SynchronizerInterface
{
    public function synchronize(ExportId $id, Shopware6Channel $channel): void;
}
