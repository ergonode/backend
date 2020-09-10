<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Category\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;

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
     * @param Language $language
     *
     * @return array
     */
    public function getAll(Language $language): array;

    /**
     * @param CategoryCode $code
     *
     * @return CategoryId|null
     */
    public function findIdByCode(CategoryCode $code):? CategoryId;

    /**
     * @param CategoryId $categoryId
     *
     * @return array|null
     */
    public function getCategory(CategoryId $categoryId): ?array;

    /**
     * @param Language    $language
     * @param string|null $search
     * @param int|null    $limit
     * @param string|null $field
     * @param string|null $order
     *
     * @return array
     */
    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;
}
