<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Category\Domain\ValueObject\CategoryCode;

interface CategoryQueryInterface
{
    public function getDictionary(Language $language): array;

    public function getAll(Language $language): array;

    public function findIdByCode(CategoryCode $code): ?CategoryId;

    public function findCodeById(CategoryId $categoryId): ?CategoryCode;

    public function getCategory(CategoryId $categoryId): ?array;

    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;
}
