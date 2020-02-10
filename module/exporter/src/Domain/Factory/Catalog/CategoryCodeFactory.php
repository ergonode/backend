<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory\Catalog;

use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategoryCode;

/**
 */
class CategoryCodeFactory
{
    /**
     * @param string $code
     *
     * @return ExportCategoryCode
     */
    public function create(string $code): ExportCategoryCode
    {
        return new ExportCategoryCode($code);
    }

    /**
     * @param \Ergonode\Category\Domain\ValueObject\CategoryCode[] $categories
     *
     * @return array
     */
    public function createList(array $categories): array
    {
        $result = [];
        foreach ($categories as $category) {
            $categoryValue = $category->getValue();
            $result[$categoryValue] = $this->create($categoryValue);
        }

        return $result;
    }
}
