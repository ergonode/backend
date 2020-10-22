<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;

interface TreeQueryInterface
{
    public function getDataSet(Language $language): DataSetInterface;

    /**
     * @return array
     */
    public function getDictionary(Language $language): array;

    public function findTreeIdByCode(string $code): ?CategoryTreeId;
}
