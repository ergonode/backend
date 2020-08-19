<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Entity\Catalog;

use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;

/**
 */
class Shopware6Category
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var ExportCategory
     */
    private ExportCategory $category;

    /**
     * @param string         $id
     * @param ExportCategory $category
     */
    public function __construct(string $id, ExportCategory $category)
    {
        $this->id = $id;
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return ExportCategory
     */
    public function getCategory(): ExportCategory
    {
        return $this->category;
    }
}
