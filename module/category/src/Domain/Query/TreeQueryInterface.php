<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

interface TreeQueryInterface
{
    public function getDictionary(Language $language): array;

    public function findTreeIdByCode(string $code): ?CategoryTreeId;

    /**
     * @return CategoryTreeId[]
     */
    public function findCategoryTreeIdsByCategoryId(CategoryId $categoryId): array;

    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;
}
