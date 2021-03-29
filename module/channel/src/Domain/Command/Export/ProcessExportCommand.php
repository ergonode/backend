<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Command\Export;

use Ergonode\Channel\Domain\Command\ExporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class ProcessExportCommand implements ExporterCommandInterface
{
    private ExportId $exportId;

    public function __construct(ExportId $exportId)
    {
        $this->exportId = $exportId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }
}
