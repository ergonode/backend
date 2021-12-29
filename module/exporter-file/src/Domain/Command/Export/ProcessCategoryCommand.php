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
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class ProcessCategoryCommand implements ExporterCommandInterface
{
    private ExportLineId $lineId;

    private ExportId $exportId;

    private CategoryId $categoryId;

    public function __construct(ExportLineId $lineId, ExportId $exportId, CategoryId $categoryId)
    {
        $this->lineId = $lineId;
        $this->exportId = $exportId;
        $this->categoryId = $categoryId;
    }

    public function getLineId(): ExportLineId
    {
        return $this->lineId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }
}
