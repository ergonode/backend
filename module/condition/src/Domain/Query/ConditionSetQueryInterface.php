<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

interface ConditionSetQueryInterface
{
    public function getDataSet(Language $language): DbalDataSet;

    /**
     * @return array
     */
    public function findAttributeIdConditionRelations(AttributeId $attributeId): array;
}
