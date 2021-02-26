<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Command\Export;

use Ergonode\Channel\Domain\Command\ExporterCommandInterface;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use JMS\Serializer\Annotation as JMS;

class CategoryExportCommand implements ExporterCommandInterface
{
    /**
     * @JMS\Type("Ergonode\Channel\Domain\ValueObject\ExportLineId")
     */
    private ExportLineId $lineId;

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

    public function __construct(
        ExportLineId $lineId,
        ExportId $exportId,
        CategoryId $categoryId,
        ?CategoryId $parentCategoryId = null
    ) {
        $this->lineId = $lineId;
        $this->exportId = $exportId;
        $this->categoryId = $categoryId;
        $this->parentCategoryId = $parentCategoryId;
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

    public function getParentCategoryId(): ?CategoryId
    {
        return $this->parentCategoryId;
    }
}
