<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

interface ProductCollectionTypeQueryInterface
{
    /**
     * @return string[]
     */
    public function getDictionary(): array;

    public function findIdByCode(ProductCollectionTypeCode $code): ?ProductCollectionTypeId;

    /**
     * @return array
     */
    public function getCollectionTypes(Language $language): array;

    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;
}
