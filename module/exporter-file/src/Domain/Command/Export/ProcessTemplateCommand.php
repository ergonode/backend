<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Command\Export;

use Ergonode\Channel\Domain\Command\ExporterCommandInterface;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class ProcessTemplateCommand implements ExporterCommandInterface
{
    private ExportLineId $lineId;

    private ExportId $exportId;

    private TemplateId $templateId;

    public function __construct(ExportLineId $lineId, ExportId $exportId, TemplateId $templateId)
    {
        $this->lineId = $lineId;
        $this->exportId = $exportId;
        $this->templateId = $templateId;
    }

    public function getLineId(): ExportLineId
    {
        return $this->lineId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getTemplateId(): TemplateId
    {
        return $this->templateId;
    }
}
