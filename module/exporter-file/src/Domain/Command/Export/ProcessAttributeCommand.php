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
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class ProcessAttributeCommand implements ExporterCommandInterface
{
    private ExportLineId $lineId;

    private ExportId $exportId;

    private AttributeId $attributeId;

    public function __construct(ExportLineId $lineId, ExportId $exportId, AttributeId $attributeId)
    {
        $this->lineId = $lineId;
        $this->exportId = $exportId;
        $this->attributeId = $attributeId;
    }

    public function getLineId(): ExportLineId
    {
        return $this->lineId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }
}
