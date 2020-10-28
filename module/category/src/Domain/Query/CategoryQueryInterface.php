<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;

interface CategoryQueryInterface
{
    public function getDataSet(Language $language): DataSetInterface;

    /**
     * @return array
     */
    public function getDictionary(Language $language): array;

    /**
     * @return array
     */
    public function getAll(Language $language): array;

    public function findIdByCode(CategoryCode $code): ?CategoryId;

    /**
     * @return array|null
     */
    public function getCategory(CategoryId $categoryId): ?array;

    /**
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
