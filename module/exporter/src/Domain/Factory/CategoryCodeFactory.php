<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory;

use Ergonode\Exporter\Domain\Entity\CategoryCode;

/**
 */
class CategoryCodeFactory
{
    /**
     * @param string $code
     *
     * @return CategoryCode
     */
    public function create(string $code): CategoryCode
    {
        return new CategoryCode($code);
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
