<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductCollectionQueryInterface
{
    /**
     * @return string[]
     */
    public function getDictionary(): array;

    /**
     * @return array
     */
    public function getOptions(Language $language): array;

    public function findIdByCode(ProductCollectionCode $code): ?ProductCollectionId;

    /**
     * @return ProductCollectionId[]
     */
    public function findProductCollectionIdByProductId(ProductId $id): array;

    /**
     * @return mixed
     */
    public function findCollectionIdsByCollectionTypeId(ProductCollectionTypeId $id);

    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;
}
