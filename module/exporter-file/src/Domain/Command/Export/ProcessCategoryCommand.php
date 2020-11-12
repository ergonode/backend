<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Command\Export;

use Ergonode\Exporter\Domain\Command\ExporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class ProcessCategoryCommand implements ExporterCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
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
