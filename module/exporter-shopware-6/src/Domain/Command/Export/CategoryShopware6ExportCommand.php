<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Command\Export;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryShopware6ExportCommand implements DomainCommandInterface
{
    /**
     * @var ExportId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private CategoryId $categoryId;

    /**
     * @var CategoryId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private ?CategoryId $parentCategoryId;

    /**
     * @param ExportId        $exportId
     * @param CategoryId      $categoryId
     * @param CategoryId|null $parentCategoryId
     */
    public function __construct(ExportId $exportId, CategoryId $categoryId, ?CategoryId $parentCategoryId = null)
    {
        $this->exportId = $exportId;
        $this->categoryId = $categoryId;
        $this->parentCategoryId = $parentCategoryId;
    }

    /**
     * @return ExportId
     */
    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    /**
     * @return CategoryId
     */
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    /**
     * @return CategoryId|null
     */
    public function getParentCategoryId(): ?CategoryId
    {
        return $this->parentCategoryId;
    }
}
