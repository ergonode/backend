<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class EndShopware6ExportProcess
{
    /**
     * @param ExportId              $id
     * @param AbstractExportProfile $profile
     */
    public function process(ExportId $id, AbstractExportProfile $profile): void
    {
    }
}
