<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory\Catalog;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 */
class CategoryCodeFactory
{
    /**
     * @param CategoryId $code
     *
     * @return UuidInterface
     */
    public function create(CategoryId $code): UuidInterface
    {
        return Uuid::fromString($code->getValue());
    }

    /**
     * @param CategoryId[] $categories
     *
     * @return array
     */
    public function createList(array $categories): array
    {
        $result = [];
        foreach ($categories as $category) {
            $result[] = $this->create($category);
        }

        return $result;
    }
}
