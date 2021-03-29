<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Command\Export;

use Ergonode\Channel\Domain\Command\ExporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class CategoryRemoveExportCommand implements ExporterCommandInterface
{
    private ExportId $exportId;

    private CategoryId $categoryId;

    public function __construct(ExportId $exportId, CategoryId $categoryId)
    {
        $this->exportId = $exportId;
        $this->categoryId = $categoryId;
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
