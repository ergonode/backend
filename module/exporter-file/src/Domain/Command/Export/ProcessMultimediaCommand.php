<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Command\Export;

use Ergonode\Channel\Domain\Command\ExporterCommandInterface;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class ProcessMultimediaCommand implements ExporterCommandInterface
{
    private ExportLineId $lineId;

    private ExportId $exportId;

    private MultimediaId $multimediaId;

    public function __construct(ExportLineId $lineId, ExportId $exportId, MultimediaId $multimediaId)
    {
        $this->lineId = $lineId;
        $this->exportId = $exportId;
        $this->multimediaId = $multimediaId;
    }

    public function getLineId(): ExportLineId
    {
        return $this->lineId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getMultimediaId(): MultimediaId
    {
        return $this->multimediaId;
    }
}
