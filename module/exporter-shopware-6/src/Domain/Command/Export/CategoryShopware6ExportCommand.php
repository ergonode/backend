<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Command\Export;

use Ergonode\Exporter\Domain\Command\ExporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use JMS\Serializer\Annotation as JMS;

class CategoryShopware6ExportCommand implements ExporterCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private CategoryId $categoryId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private ?CategoryId $parentCategoryId;

    public function __construct(ExportId $exportId, CategoryId $categoryId, ?CategoryId $parentCategoryId = null)
    {
        $this->exportId = $exportId;
        $this->categoryId = $categoryId;
        $this->parentCategoryId = $parentCategoryId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    public function getParentCategoryId(): ?CategoryId
    {
        return $this->parentCategoryId;
    }
}
