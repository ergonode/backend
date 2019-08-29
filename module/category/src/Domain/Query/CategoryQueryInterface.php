<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Category\Domain\Query;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface CategoryQueryInterface
{
    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface;

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array;

    /**
     * @param CategoryId $categoryId
     *
     * @return array|null
     */
    public function getCategory(CategoryId $categoryId): ?array;
}
